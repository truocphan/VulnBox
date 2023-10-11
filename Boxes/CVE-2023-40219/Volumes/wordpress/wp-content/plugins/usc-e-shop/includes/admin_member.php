<?php
/**
 * Member Page Settings.
 *
 * @package  Welcart
 */

$agree_member = ( isset( $this->options['agree_member'] ) && ! empty( $this->options['agree_member'] ) ) ? $this->options['agree_member'] : 'deactivate';
if ( ! empty( $this->options['member_page_data'] ) ) {
	$member_page_datas = stripslashes_deep( $this->options['member_page_data'] );
} else {
	$member_page_datas = array();
}
$agree_member_exp  = ( isset( $member_page_datas['agree_member_exp'] ) ) ? $member_page_datas['agree_member_exp'] : '';
$agree_member_cont = ( isset( $member_page_datas['agree_member_cont'] ) ) ? $member_page_datas['agree_member_cont'] : '';
$login_header      = ( isset( $member_page_datas['header']['login'] ) ) ? $member_page_datas['header']['login'] : '';
$login_footer      = ( isset( $member_page_datas['footer']['login'] ) ) ? $member_page_datas['footer']['login'] : '';
$newmember_header  = ( isset( $member_page_datas['header']['newmember'] ) ) ? $member_page_datas['header']['newmember'] : '';
$newmember_footer  = ( isset( $member_page_datas['footer']['newmember'] ) ) ? $member_page_datas['footer']['newmember'] : '';
$newpass_header    = ( isset( $member_page_datas['header']['newpass'] ) ) ? $member_page_datas['header']['newpass'] : '';
$newpass_footer    = ( isset( $member_page_datas['footer']['newpass'] ) ) ? $member_page_datas['footer']['newpass'] : '';
$changepass_header = ( isset( $member_page_datas['header']['changepass'] ) ) ? $member_page_datas['header']['changepass'] : '';
$changepass_footer = ( isset( $member_page_datas['footer']['changepass'] ) ) ? $member_page_datas['footer']['changepass'] : '';
$memberinfo_header = ( isset( $member_page_datas['header']['memberinfo'] ) ) ? $member_page_datas['header']['memberinfo'] : '';
$memberinfo_footer = ( isset( $member_page_datas['footer']['memberinfo'] ) ) ? $member_page_datas['footer']['memberinfo'] : '';
$completion_header = ( isset( $member_page_datas['header']['completion'] ) ) ? $member_page_datas['header']['completion'] : '';
$completion_footer = ( isset( $member_page_datas['footer']['completion'] ) ) ? $member_page_datas['footer']['completion'] : '';
?>
<script type="text/javascript">
jQuery(function($) {
	$( "#uscestabs_member" ).tabs({
		active: ( $.cookie( "uscestabs_member" ) ) ? $.cookie( "uscestabs_member" ) : 0
		, activate: function( event, ui ) {
			$.cookie( "uscestabs_member", $(this).tabs( "option", "active" ) );
		}
	});

	customField = {
		settings: {
			url: uscesL10n.requestFile,
			type: 'POST',
			cache: false
		},

		//** Custom Member **
		addMember: function() {
			var key = $( "#newcsmbkey" ).val();
			var name = $( "#newcsmbname" ).val();
			var value = $( "#newcsmbvalue" ).val();
			var means = $( "#newcsmbmeans" ).val();
			var essential = ( $( "input#newcsmbessential" ).prop( "checked" ) ) ? '1' : '0';
			var position = $( "#newcsmbposition" ).val();
			var mes = '';
			if( '' == key || !checkCode( key ) ) {
				mes += '<p>'+uscesL10n.message[21]+'</p>';
			}
			if( '' == name ) {
				mes += '<p>'+uscesL10n.message[22]+'</p>';
			}
			if( '' == value && ( 0 == means || 1 == means || 3 == means || 4 == means ) ) {
				mes += '<p>'+uscesL10n.message[2]+'</p>';
			} else if( '' != value && ( 2 == means || 5 == means ) ) {
				mes += '<p>'+uscesL10n.message[3]+'</p>';
			}
			if( '' != mes ) {
				mes = '<div class="error">'+mes+'</div>';
				$( "#ajax-response-csmb" ).html( mes );
				return false;
			}

			$( "#newcsmb_loading" ).html( '<img src="'+uscesL10n.USCES_PLUGIN_URL+'/images/loading.gif" />' );

			var s = customField.settings;
			s.data = {
				action: 'custom_field_ajax',
				field: 'member',
				add: 1,
				newkey: key,
				newname: name,
				newvalue: value,
				newmeans: means,
				newessential: essential,
				newposition: position,
				wc_nonce: '<?php echo wp_create_nonce( 'custom_field_ajax' ); ?>'
			}
			s.dataType = 'json';
			$.ajax( s ).done(function( data ) {
				$( "#ajax-response-csmb" ).html( '' );
				$( "#newcsmb_loading" ).html( '' );
				if( 'OK' == data.status ) {
					if( 0 < data.dupkey ) {
						$( "#ajax-response-csmb" ).html( '<div class="error"><p>'+uscesL10n.message[23]+'</p></div>' );
					} else {
						if( data.list.length > 1 ) $( "table#csmb-list-table" ).removeAttr( "style" );
						$( "tbody#csmb-list" ).html( data.list );
						$( "#csmb-"+key ).css( { 'background-color': '#FF4' } );
						$( "#csmb-"+key ).animate( { 'background-color': '#FFFFEE' }, 2000 );
						$( "#newcsmbkey" ).val( "" );
						$( "#newcsmbname" ).val( "" );
						$( "#newcsmbvalue" ).val( "" );
						$( "#newcsmbmeans" ).val( 0 );
						$( "#newcsmbessential" ).prop( "checked", false );
					}
				} else {
					if( 0 < data.msg.length ) $( "#ajax-response-csmb" ).html( data.msg );
				}
			}).fail(function( msg ) {
				$( "#ajax-response-csmb" ).html( msg );
				$( "#newcsmb_loading" ).html( '' );
			});
			return false;
		},

		updMember: function( key ) {
			var name = $( ':input[name="csmb['+key+'][name]"]' ).val();
			var value = $( ':input[name="csmb['+key+'][value]"]' ).val();
			var means = $( ':input[name="csmb['+key+'][means]"]' ).val();
			var essential = ( $( ':input[name="csmb['+key+'][essential]"]' ).prop( "checked" ) ) ? '1' : '0';
			var position = $( ':input[name="csmb['+key+'][position]"]' ).val();
			var mes = '';
			if( '' == key || !checkCode( key ) ) {
				mes += '<p>'+uscesL10n.message[21]+'</p>';
			}
			if( '' == name ) {
				mes += '<p>'+uscesL10n.message[22]+'</p>';
			}
			if( '' == value && ( 0 == means || 1 == means || 3 == means || 4 == means ) ) {
				mes += '<p>'+uscesL10n.message[2]+'</p>';
			} else if( '' != value && ( 2 == means || 5 == means ) ) {
				mes += '<p>'+uscesL10n.message[3]+'</p>';
			}
			if( '' != mes ) {
				mes = '<div class="error">'+mes+'</div>';
				$( "#ajax-response-csmb" ).html( mes );
				return false;
			}

			$( "#csmb_loading-"+key ).html( '<img src="'+uscesL10n.USCES_PLUGIN_URL+'/images/loading.gif" />' );

			var s = customField.settings;
			s.data = {
				action: 'custom_field_ajax',
				field: 'member',
				update: 1,
				key: key,
				name: name,
				value: value,
				means: means,
				essential: essential,
				position: position,
				wc_nonce: '<?php echo wp_create_nonce( 'custom_field_ajax' ); ?>'
			}
			s.dataType = 'json';
			$.ajax( s ).done(function( data ) {
				$( "#ajax-response-csmb" ).html( '' );
				$( "#csmb_loading-"+key ).html( '' );
				if( 'OK' == data.status ) {
					$( "tbody#csmb-list" ).html( data.list );
					$( "#csmb-"+key ).css( { 'background-color': '#FF4' } );
					$( "#csmb-"+key ).animate( { 'background-color': '#FFFFEE' }, 2000 );
				} else {
					if( 0 < data.msg.length ) $( "#ajax-response-csmb" ).html( data.msg );
				}
			}).fail(function( msg ) {
				$( "#ajax-response-csmb" ).html( msg );
				$( "#csmb_loading-"+key ).html( '' );
			});
			return false;
		},

		delMember: function( key ) {
			$( "#csmb-"+key ).css( { 'background-color': '#F00' } );
			$( "#csmb-"+key ).animate( { 'background-color': '#FFFFEE' }, 1000 );
			var s = customField.settings;
			s.data = {
				action: 'custom_field_ajax',
				field: 'member',
				delete: 1,
				key: key,
				wc_nonce: '<?php echo wp_create_nonce( 'custom_field_ajax' ); ?>'
			}
			s.dataType = 'json';
			$.ajax( s ).done(function( data ) {
				$( "#ajax-response-csmb" ).html( '' );
				if( 'OK' == data.status ) {
					$( "tbody#csmb-list" ).html( data.list );
					if( data.list.length < 1 ) $( "table#csmb-list-table" ).attr( "style", "display: none" );
				} else {
					if( 0 < data.msg.length ) $( "#ajax-response-csmb" ).html( data.msg );
				}
			}).fail(function( msg ) {
				$( "#ajax-response-csmb" ).html( msg );
			});
			return false;
		},

		addAdmb: function() {
			var key = $( "#newadmbkey" ).val();
			var name = $( "#newadmbname" ).val();
			var value = $( "#newadmbvalue" ).val();
			var means = $( "#newadmbmeans" ).val();
			var essential = ( $( "input#newadmbessential" ).prop( "checked" ) ) ? '1' : '0';
			var position = $( "#newadmbposition" ).val();
			var mes = '';
			if( '' == key || !checkCode( key ) ) {
				mes += '<p>'+uscesL10n.message[21]+'</p>';
			}
			if( '' == name ) {
				mes += '<p>'+uscesL10n.message[22]+'</p>';
			}
			if( '' == value && ( 0 == means || 1 == means || 3 == means || 4 == means ) ) {
				mes += '<p>'+uscesL10n.message[2]+'</p>';
			} else if( '' != value && ( 2 == means || 5 == means ) ) {
				mes += '<p>'+uscesL10n.message[3]+'</p>';
			}
			if( '' != mes ) {
				mes = '<div class="error">'+mes+'</div>';
				$( "#ajax-response-admb" ).html( mes );
				return false;
			}

			$( "#newadmb_loading" ).html( '<img src="'+uscesL10n.USCES_PLUGIN_URL+'/images/loading.gif" />' );

			var s = customField.settings;
			s.data = {
				action: 'custom_field_ajax',
				field: 'admin_member',
				add: 1,
				newkey: key,
				newname: name,
				newvalue: value,
				newmeans: means,
				newessential: essential,
				newposition: position,
				wc_nonce: '<?php echo wp_create_nonce( 'custom_field_ajax' ); ?>'
			}
			s.dataType = 'json';
			$.ajax( s ).done(function( data ) {
				$( "#ajax-response-admb" ).html( '' );
				$( "#newadmb_loading" ).html( '' );
				if( 'OK' == data.status ) {
					if( 0 < data.dupkey ) {
						$( "#ajax-response-admb" ).html( '<div class="error"><p>'+uscesL10n.message[23]+'</p></div>' );
					} else {
						if( data.list.length > 1 ) $( "table#admb-list-table" ).removeAttr( "style" );
						$( "tbody#admb-list" ).html( data.list );
						$( "#admb-"+key ).css( { 'background-color': '#FF4' } );
						$( "#admb-"+key ).animate( { 'background-color': '#FFFFEE' }, 2000 );
						$( "#newadmbkey" ).val( "" );
						$( "#newadmbname" ).val( "" );
						$( "#newadmbvalue" ).val( "" );
						$( "#newadmbmeans" ).val( 0 );
						$( "#newadmbessential" ).prop( "checked", false );
					}
				} else {
					if( 0 < data.msg.length ) $( "#ajax-response-admb" ).html( data.msg );
				}
			}).fail(function( msg ) {
				$( "#ajax-response-admb" ).html( msg );
				$( "#newadmb_loading" ).html( '' );
			});
			return false;
		},

		updAdmb: function( key ) {
			var name = $( ':input[name="admb['+key+'][name]"]' ).val();
			var value = $( ':input[name="admb['+key+'][value]"]' ).val();
			var means = $( ':input[name="admb['+key+'][means]"]' ).val();
			var essential = ( $( ':input[name="admb['+key+'][essential]"]' ).prop( "checked" ) ) ? '1' : '0';
			var position = $( ':input[name="admb['+key+'][position]"]' ).val();
			var mes = '';
			if( '' == key || !checkCode( key ) ) {
				mes += '<p>'+uscesL10n.message[21]+'</p>';
			}
			if( '' == name ) {
				mes += '<p>'+uscesL10n.message[22]+'</p>';
			}
			if( '' == value && ( 0 == means || 1 == means || 3 == means || 4 == means ) ) {
				mes += '<p>'+uscesL10n.message[2]+'</p>';
			} else if( '' != value && ( 2 == means || 5 == means ) ) {
				mes += '<p>'+uscesL10n.message[3]+'</p>';
			}
			if( '' != mes ) {
				mes = '<div class="error">'+mes+'</div>';
				$( "#ajax-response-admb" ).html( mes );
				return false;
			}

			$( "#admb_loading-"+key ).html( '<img src="'+uscesL10n.USCES_PLUGIN_URL+'/images/loading.gif" />' );

			var s = customField.settings;
			s.data = {
				action: 'custom_field_ajax',
				field: 'admin_member',
				update: 1,
				key: key,
				name: name,
				value: value,
				means: means,
				essential: essential,
				position: position,
				wc_nonce: '<?php echo wp_create_nonce( 'custom_field_ajax' ); ?>'
			}
			s.dataType = 'json';
			$.ajax( s ).done(function( data ) {
				$( "#ajax-response-admb" ).html( '' );
				$( "#admb_loading-"+key ).html( '' );
				if( 'OK' == data.status ) {
					$( "tbody#admb-list" ).html( data.list );
					$( "#admb-"+key ).css( { 'background-color': '#FF4' } );
					$( "#admb-"+key ).animate( { 'background-color': '#FFFFEE' }, 2000 );
				} else {
					if( 0 < data.msg.length ) $( "#ajax-response-admb" ).html( data.msg );
				}
			}).fail(function( msg ) {
				$( "#ajax-response-admb" ).html( msg );
				$( "#admb_loading-"+key ).html( '' );
			});
			return false;
		},

		delAdmb: function( key ) {
			$( "#admb-"+key ).css( { 'background-color': '#F00' } );
			$( "#admb-"+key ).animate( { 'background-color': '#FFFFEE' }, 1000 );
			var s = customField.settings;
			s.data = {
				action: 'custom_field_ajax',
				field: 'admin_member',
				delete: 1,
				key: key,
				wc_nonce: '<?php echo wp_create_nonce( 'custom_field_ajax' ); ?>'
			}
			s.dataType = 'json';
			$.ajax( s ).done(function( data ) {
				$( "#ajax-response-admb" ).html( '' );
				if( 'OK' == data.status ) {
					$( "tbody#admb-list" ).html( data.list );
					if( data.list.length < 1 ) $( "table#admb-list-table" ).attr( "style", "display: none" );
				} else {
					if( 0 < data.msg.length ) $( "#ajax-response-admb" ).html( data.msg );
				}
			}).fail(function( msg ) {
				$( "#ajax-response-admb" ).html( msg );
			});
			return false;
		},
	};

});
</script>
<div class="wrap">
<div class="usces_admin">
<h1>Welcart Shop <?php esc_html_e( 'Member Page Setting', 'usces' ); ?></h1>
<?php usces_admin_action_status(); ?>
<form action="" method="post" name="option_form" id="option_form">
<input name="usces_option_update" type="submit" class="button button-primary" value="<?php esc_attr_e( 'change decision', 'usces' ); ?>" />
<div id="poststuff" class="metabox-holder">
<div class="uscestabs" id="uscestabs_member">
<ul>
	<li><a href="#member_page_setting_0"><?php esc_html_e( 'Member Page Setting', 'usces' ); ?></a></li>
	<li><a href="#member_page_setting_1"><?php esc_html_e( 'Explanation in Member page', 'usces' ); ?></a></li>
	<li><a href="#member_page_setting_2"><?php esc_html_e( 'Custom member field', 'usces' ); ?></a></li>
	<li><a href="#member_page_setting_3"><?php esc_html_e( 'Admin custom field', 'usces' ); ?></a></li>
	<?php do_action( 'usces_action_admin_member_tab_label' ); ?>
</ul>

<div id="member_page_setting_0">
	<div class="postbox">
	<h3><span><?php esc_html_e( 'Member Page Setting', 'usces' ); ?></span><a style="cursor:pointer;" onclick="toggleVisibility('ex_member_page_setting');"><?php esc_html_e( '(Explain)', 'usces' ); ?></a></h3>
	<div class="inside">
		<table class="form_table">
			<tr>
				<th><a style="cursor:pointer;" onclick="toggleVisibility('ex_agree_member');"><?php esc_html_e( 'Membership Agreement', 'usces' ); ?></a></th>
				<td width="10"><input name="agree_member" id="agree_member_activate" value="activate" type="radio"<?php checked( $agree_member, 'activate' ); ?>></td><td width="100"><label for="agree_member_activate"><?php esc_html_e( 'Seek', 'usces' ); ?></label></td>
				<td width="10"><input name="agree_member" id="agree_member_deactivate" value="deactivate" type="radio"<?php checked( $agree_member, 'deactivate' ); ?>></td><td width="100"><label for="agree_member_deactivate"><?php esc_html_e( 'Not seek', 'usces' ); ?></label></td>
				<td><div id="ex_agree_member" class="explanation"><?php esc_html_e( 'Whether or not seek consent to the membership agreement at the time of member registration', 'usces' ); ?></div></td>
			</tr>
		</table>
		<table class="form_table">
			<tr>
				<th><?php esc_html_e( 'Explanation of membership', 'usces' ); ?></th>
				<td><textarea name="agree_member_exp" id="agree_member_exp" class="textarea_fld"><?php wel_esc_script_e( $agree_member_exp ); ?></textarea></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Text of membership', 'usces' ); ?></th>
				<td><textarea name="agree_member_cont" id="agree_member_cont" class="textarea_fld"><?php wel_esc_script_e( $agree_member_cont ); ?></textarea></td>
				<td>&nbsp;</td>
			</tr>
		</table>
		<hr size="1" color="#CCCCCC" />
		<div id="ex_member_page_setting" class="explanation"><?php esc_html_e( 'Make the various settings in the member page.', 'usces' ); ?></div>
	</div>
	</div>
</div><!--member_page_setting_0-->

<div id="member_page_setting_1">
	<div class="postbox">
	<h3><span><?php esc_html_e( 'Explanation in a Login page', 'usces' ); ?></span><a style="cursor:pointer;" onclick="toggleVisibility('ex_login_page');"><?php esc_html_e( '(Explain)', 'usces' ); ?></a></h3>
	<div class="inside">
	<table class="form_table">
		<tr>
			<th><?php esc_html_e( 'header', 'usces' ); ?></th>
			<td><textarea name="header[login]" id="header[login]" class="textarea_fld"><?php wel_esc_script_e( $login_header ); ?></textarea></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<th><?php esc_html_e( 'footer', 'usces' ); ?></th>
			<td><textarea name="footer[login]" id="footer[login]" class="textarea_fld"><?php wel_esc_script_e( $login_footer ); ?></textarea></td>
			<td>&nbsp;</td>
		</tr>
	</table>
	<hr size="1" color="#CCCCCC" />
	<div id="ex_login_page" class="explanation"><?php esc_html_e( 'You can set additional explanation to insert in a login page.', 'usces' ); ?></div>
	</div>
	</div><!--postbox-->

	<div class="postbox">
	<h3><span><?php esc_html_e( 'Explanation in a New Member page', 'usces' ); ?></span><a style="cursor:pointer;" onclick="toggleVisibility('ex_newmember_page');"><?php esc_html_e( '(Explain)', 'usces' ); ?></a></h3>
	<div class="inside">
	<table class="form_table">
		<tr>
			<th><?php esc_html_e( 'header', 'usces' ); ?></th>
			<td><textarea name="header[newmember]" id="header[newmember]" class="textarea_fld"><?php wel_esc_script_e( $newmember_header ); ?></textarea></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<th><?php esc_html_e( 'footer', 'usces' ); ?></th>
			<td><textarea name="footer[newmember]" id="footer[newmember]" class="textarea_fld"><?php wel_esc_script_e( $newmember_footer ); ?></textarea></td>
			<td>&nbsp;</td>
		</tr>
	</table>
	<hr size="1" color="#CCCCCC" />
	<div id="ex_newmember_page" class="explanation"><?php esc_html_e( 'You can set additional explanation to insert in a new member page.', 'usces' ); ?></div>
	</div>
	</div><!--postbox-->

	<div class="postbox">
	<h3><span><?php esc_html_e( 'Explanation in New Password page', 'usces' ); ?></span><a style="cursor:pointer;" onclick="toggleVisibility('ex_newpass_page');"><?php esc_html_e( '(Explain)', 'usces' ); ?></a></h3>
	<div class="inside">
	<table class="form_table">
		<tr>
			<th><?php esc_html_e( 'header', 'usces' ); ?></th>
			<td><textarea name="header[newpass]" id="header[newpass]" class="textarea_fld"><?php wel_esc_script_e( $newpass_header ); ?></textarea></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<th><?php esc_html_e( 'footer', 'usces' ); ?></th>
			<td><textarea name="footer[newpass]" id="footer[newpass]" class="textarea_fld"><?php wel_esc_script_e( $newpass_footer ); ?></textarea></td>
			<td>&nbsp;</td>
		</tr>
	</table>
	<hr size="1" color="#CCCCCC" />
	<div id="ex_newpass_page" class="explanation"><?php esc_html_e( 'You can set additional explanation to insert in a new password page.', 'usces' ); ?></div>
	</div>
	</div><!--postbox-->

	<div class="postbox">
	<h3><span><?php esc_html_e( 'Explanation in a Change Password page', 'usces' ); ?></span><a style="cursor:pointer;" onclick="toggleVisibility('ex_changepass_page');"><?php esc_html_e( '(Explain)', 'usces' ); ?></a></h3>
	<div class="inside">
	<table class="form_table">
		<tr>
			<th><?php esc_html_e( 'header', 'usces' ); ?></th>
			<td><textarea name="header[changepass]" id="header[changepass]" class="textarea_fld"><?php wel_esc_script_e( $changepass_header ); ?></textarea></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<th><?php esc_html_e( 'footer', 'usces' ); ?></th>
			<td><textarea name="footer[changepass]" id="footer[changepass]" class="textarea_fld"><?php wel_esc_script_e( $changepass_footer ); ?></textarea></td>
			<td>&nbsp;</td>
		</tr>
	</table>
	<hr size="1" color="#CCCCCC" />
	<div id="ex_changepass_page" class="explanation"><?php esc_html_e( 'You can set additional explanation to insert in a change password page.', 'usces' ); ?></div>
	</div>
	</div><!--postbox-->

	<div class="postbox">
	<h3><span><?php esc_html_e( 'Explanation in a Member Information page', 'usces' ); ?></span><a style="cursor:pointer;" onclick="toggleVisibility('ex_memberinfo_page');"><?php esc_html_e( '(Explain)', 'usces' ); ?></a></h3>
	<div class="inside">
	<table class="form_table">
		<tr>
			<th><?php esc_html_e( 'header', 'usces' ); ?></th>
			<td><textarea name="header[memberinfo]" id="header[memberinfo]" class="textarea_fld"><?php wel_esc_script_e( $memberinfo_header ); ?></textarea></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<th><?php esc_html_e( 'footer', 'usces' ); ?></th>
			<td><textarea name="footer[memberinfo]" id="footer[memberinfo]" class="textarea_fld"><?php wel_esc_script_e( $memberinfo_footer ); ?></textarea></td>
			<td>&nbsp;</td>
		</tr>
	</table>
	<hr size="1" color="#CCCCCC" />
	<div id="ex_memberinfo_page" class="explanation"><?php esc_html_e( 'You can set additional explanation to insert in a member information page.', 'usces' ); ?></div>
	</div>
	</div><!--postbox-->

	<div class="postbox">
	<h3><span><?php esc_html_e( 'Explanation in a Completion page', 'usces' ); ?></span><a style="cursor:pointer;" onclick="toggleVisibility('ex_completion_page');"><?php esc_html_e( '(Explain)', 'usces' ); ?></a></h3>
	<div class="inside">
	<table class="form_table">
		<tr>
			<th><?php esc_html_e( 'header', 'usces' ); ?></th>
			<td><textarea name="header[completion]" id="header[completion]" class="textarea_fld"><?php wel_esc_script_e( $completion_header ); ?></textarea></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<th><?php esc_html_e( 'footer', 'usces' ); ?></th>
			<td><textarea name="footer[completion]" id="footer[completion]" class="textarea_fld"><?php wel_esc_script_e( $completion_footer ); ?></textarea></td>
			<td>&nbsp;</td>
		</tr>
	</table>
	<hr size="1" color="#CCCCCC" />
	<div id="ex_completion_page" class="explanation"><?php esc_html_e( 'You can set additional explanation to insert in a completion page.', 'usces' ); ?></div>
	</div>
	</div><!--postbox-->
</div><!--member_page_setting_1-->
<?php
$csmb_meta        = usces_has_custom_field_meta( 'member' );
$csmb_display     = ( empty( $csmb_meta ) ) ? ' style="display: none;"' : '';
$csmb_means       = get_option( 'usces_custom_member_select' );
$csmb_meansoption = '';
foreach ( $csmb_means as $meankey => $meanvalue ) {
	$csmb_meansoption .= '<option value="' . esc_attr( $meankey ) . '">' . esc_html( $meanvalue ) . "</option>\n";
}
$positions       = get_option( 'usces_custom_field_position_select' );
$positionsoption = '';
foreach ( $positions as $poskey => $posvalue ) {
	$positionsoption .= '<option value="' . esc_attr( $poskey ) . '">' . esc_html( $posvalue ) . "</option>\n";
}
?>
<div id="member_page_setting_2">
	<div class="postbox">
	<h3><span><?php esc_html_e( 'Custom member field', 'usces' ); ?><a style="cursor:pointer;" onclick="toggleVisibility('ex_custom_member');"><?php esc_html_e( '(Explain)', 'usces' ); ?></a></span></h3>
	<div class="inside">
	<div id="postoptcustomstuff">
	<table id="csmb-list-table" class="list"<?php echo esc_attr( $csmb_display ); ?>>
		<thead>
		<tr>
		<th class="left"><?php esc_html_e( 'key name', 'usces' ); ?></th>
		<th rowspan="2"><?php esc_html_e( 'selected amount', 'usces' ); ?></th>
		</tr>
		<tr>
		<th class="left"><?php esc_html_e( 'field name', 'usces' ); ?></th>
		</tr>
		</thead>
		<tbody id="csmb-list">
<?php
if ( is_array( $csmb_meta ) ) {
	foreach ( $csmb_meta as $key => $entry ) {
		echo _list_custom_member_meta_row( $key, $entry );
	}
}
?>
		</tbody>
	</table>
	<div id="ajax-response-csmb"></div>
	<p><strong><?php esc_html_e( 'Add a new custom member field', 'usces' ); ?> : </strong></p>
	<table id="newmeta2">
		<thead>
		<tr>
		<th class="left"><?php esc_html_e( 'key name', 'usces' ); ?></th>
		<th rowspan="2"><?php esc_html_e( 'selected amount', 'usces' ); ?></th>
		</tr>
		<tr>
		<th class="left"><?php esc_html_e( 'field name', 'usces' ); ?></th>
		</tr>
		</thead>

		<tbody>
		<tr>
		<td class="item-opt-key">
		<input type="text" name="newcsmbkey" id="newcsmbkey" class="optname" value="" />
		<input type="text" name="newcsmbname" id="newcsmbname" class="optname" value="" />
		<div class="optcheck"><select name="newcsmbmeans" id="newcsmbmeans"><?php wel_esc_script_e( $csmb_meansoption ); ?></select>
		<input type="checkbox" name="newcsmbessential" id="newcsmbessential" /><label for="newcsmbessential"><?php esc_html_e( 'Required', 'usces' ); ?></label>
		<select name="newcsmbposition" id="newcsmbposition"><?php wel_esc_script_e( $positionsoption ); ?></select></div>
		</td>
		<td class="item-opt-value"><textarea name="newcsmbvalue" id="newcsmbvalue" class="optvalue"></textarea></td>
		</tr>

		<tr><td colspan="2" class="submit">
		<input type="button" class="button" name="add_csmb" id="add_csmb" value="<?php esc_attr_e( 'Add custom member field', 'usces' ); ?>" onclick="customField.addMember();" />
		<div id="newcsmb_loading" class="meta_submit_loading"></div>
		</td></tr>
		</tbody>
	</table>

	<hr size="1" color="#CCCCCC" />
	<div id="ex_custom_member" class="explanation"><?php esc_html_e( 'You can add an arbitrary field to the member information page.', 'usces' ); ?></div>
	</div>
	</div>
	</div><!--postbox-->
</div><!--member_page_setting_2-->
<?php
$admb_meta        = usces_has_custom_field_meta( 'admin_member' );
$admb_display     = ( empty( $admb_meta ) ) ? ' style="display: none;"' : '';
$admb_means       = get_option( 'usces_custom_member_select' );
$admb_meansoption = '';
foreach ( $admb_means as $meankey => $meanvalue ) {
	$admb_meansoption .= '<option value="' . esc_attr( $meankey ) . '">' . esc_html( $meanvalue ) . "</option>\n";
}
?>
<div id="member_page_setting_3">
	<div class="postbox">
	<h3><span><?php esc_html_e( 'Admin custom field', 'usces' ); ?><a style="cursor:pointer;" onclick="toggleVisibility('ex_admin_custom_member');"><?php esc_html_e( '(Explain)', 'usces' ); ?></a></span></h3>
	<div class="inside">
	<div id="postoptcustomstuff">
	<table id="admb-list-table" class="list"<?php echo esc_attr( $admb_display ); ?>>
		<thead>
		<tr>
		<th class="left"><?php esc_html_e( 'key name', 'usces' ); ?></th>
		<th rowspan="2"><?php esc_html_e( 'selected amount', 'usces' ); ?></th>
		</tr>
		<tr>
		<th class="left"><?php esc_html_e( 'field name', 'usces' ); ?></th>
		</tr>
		</thead>
		<tbody id="admb-list">
<?php
if ( is_array( $admb_meta ) ) {
	foreach ( $admb_meta as $key => $entry ) {
		echo _list_admin_custom_member_meta_row( $key, $entry );
	}
}
?>
		</tbody>
	</table>
	<div id="ajax-response-admb"></div>
	<p><strong><?php esc_html_e( 'Add a new admin custom field', 'usces' ); ?> : </strong></p>
	<table id="newmeta2">
		<thead>
		<tr>
		<th class="left"><?php esc_html_e( 'key name', 'usces' ); ?></th>
		<th rowspan="2"><?php esc_html_e( 'selected amount', 'usces' ); ?></th>
		</tr>
		<tr>
		<th class="left"><?php esc_html_e( 'field name', 'usces' ); ?></th>
		</tr>
		</thead>

		<tbody>
		<tr>
		<td class="item-opt-key">
		<input type="text" name="newadmbkey" id="newadmbkey" class="optname" value="" />
		<input type="text" name="newadmbname" id="newadmbname" class="optname" value="" />
		<div class="optcheck"><select name="newadmbmeans" id="newadmbmeans"><?php wel_esc_script_e( $admb_meansoption ); ?></select>
		<input type="checkbox" name="newadmbessential" id="newadmbessential" /><label for="newadmbessential"><?php esc_html_e( 'Required', 'usces' ); ?></label>
		</div>
		</td>
		<td class="item-opt-value"><textarea name="newadmbvalue" id="newadmbvalue" class="optvalue"></textarea></td>
		</tr>

		<tr><td colspan="2" class="submit">
		<input type="button" class="button" name="add_admb" id="add_admb" value="<?php esc_attr_e( 'Add admin custom member field', 'usces' ); ?>" onclick="customField.addAdmb();" />
		<div id="newadmb_loading" class="meta_submit_loading"></div>
		</td></tr>
		</tbody>
	</table>

	<hr size="1" color="#CCCCCC" />
	<div id="ex_admin_custom_member" class="explanation"><?php esc_html_e( 'You can add any custom field for the administrator only. It will not be displayed to the members.', 'usces' ); ?></div>
	</div>
	</div>
	</div><!--postbox-->
</div><!--member_page_setting_3-->
<?php do_action( 'usces_action_admin_member_tab_body' ); ?>
</div><!--uscestabs_member-->
</div><!--poststuff-->
<input name="usces_option_update" type="submit" class="button button-primary" value="<?php esc_attr_e( 'change decision', 'usces' ); ?>" />
<?php wp_nonce_field( 'admin_member', 'wc_nonce' ); ?>
</form>
</div><!--usces_admin-->
</div><!--wrap-->
