<?php
/**
 * Cart Page Settings.
 *
 * @package  Welcart
 */

if ( ! empty( $this->options['cart_page_data'] ) ) {
	$cart_page_datas = stripslashes_deep( $this->options['cart_page_data'] );
} else {
	$cart_page_datas['header'] = array();
	$cart_page_datas['footer'] = array();
}
$indi_item_name = $this->options['indi_item_name'];
$pos_item_name  = $this->options['pos_item_name'];
foreach ( (array) $indi_item_name as $key => $value ) {
	$checked_item_name[ $key ] = ( 1 == $indi_item_name[ $key ] ) ? ' checked="checked"' : '';
}
$cart_header       = ( isset( $cart_page_datas['header']['cart'] ) ) ? $cart_page_datas['header']['cart'] : '';
$cart_footer       = ( isset( $cart_page_datas['footer']['cart'] ) ) ? $cart_page_datas['footer']['cart'] : '';
$customer_header   = ( isset( $cart_page_datas['header']['customer'] ) ) ? $cart_page_datas['header']['customer'] : '';
$customer_footer   = ( isset( $cart_page_datas['footer']['customer'] ) ) ? $cart_page_datas['footer']['customer'] : '';
$delivery_header   = ( isset( $cart_page_datas['header']['delivery'] ) ) ? $cart_page_datas['header']['delivery'] : '';
$delivery_footer   = ( isset( $cart_page_datas['footer']['delivery'] ) ) ? $cart_page_datas['footer']['delivery'] : '';
$confirm_header    = ( isset( $cart_page_datas['header']['confirm'] ) ) ? $cart_page_datas['header']['confirm'] : '';
$confirm_footer    = ( isset( $cart_page_datas['footer']['confirm'] ) ) ? $cart_page_datas['footer']['confirm'] : '';
$completion_header = ( isset( $cart_page_datas['header']['completion'] ) ) ? $cart_page_datas['header']['completion'] : '';
$completion_footer = ( isset( $cart_page_datas['footer']['completion'] ) ) ? $cart_page_datas['footer']['completion'] : '';
$confirm_notes     = ( isset( $cart_page_datas['confirm_notes'] ) ) ? $cart_page_datas['confirm_notes'] : '';
?>
<script type="text/javascript">
jQuery(function($) {

	$(".num").bind("change",function(){usces_check_num($(this));});

	$( "#uscestabs_cart" ).tabs({
		active: ( $.cookie( "uscestabs_cart" ) ) ? $.cookie( "uscestabs_cart" ) : 0
		, activate: function( event, ui ) {
			$.cookie( "uscestabs_cart", $(this).tabs( "option", "active" ) );
		}
	});

	customField = {
		settings: {
			url: uscesL10n.requestFile,
			type: 'POST',
			cache: false
		},

		//** Custom Order **
		addOrder: function() {
			var key = $( "#newcsodkey" ).val();
			var name = $( "#newcsodname" ).val();
			var value = $( "#newcsodvalue" ).val();
			var means = $( "#newcsodmeans" ).val();
			var essential = ( $( "input#newcsodessential" ).prop( "checked" ) ) ? '1' : '0';
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
				$( "#ajax-response-csod" ).html( mes );
				return false;
			}

			$( "#newcsod_loading" ).html( '<img src="'+uscesL10n.USCES_PLUGIN_URL+'/images/loading.gif" />' );

			var s = customField.settings;
			s.data = {
				action: 'custom_field_ajax',
				field: 'order',
				add: 1,
				newkey: key,
				newname: name,
				newvalue: value,
				newmeans: means,
				newessential: essential,
				wc_nonce: '<?php echo wp_create_nonce( 'custom_field_ajax' ); ?>'
			}
			s.dataType = 'json';
			$.ajax( s ).done(function( data ) {
				$( "#ajax-response-csod" ).html( '' );
				$( "#newcsod_loading" ).html( '' );
				if( 'OK' == data.status ) {
					if( 0 < data.dupkey ) {
						$( "#ajax-response-csod" ).html( '<div class="error"><p>'+uscesL10n.message[23]+'</p></div>' );
					} else {
						if( data.list.length > 1 ) $( "table#csod-list-table" ).removeAttr( "style" );
						$( "tbody#csod-list" ).html( data.list );
						$( "#csod-"+key ).css( { 'background-color': '#FF4' } );
						$( "#csod-"+key ).animate( { 'background-color': '#FFFFEE' }, 2000 );
						$( "#newcsodkey" ).val( "" );
						$( "#newcsodname" ).val( "" );
						$( "#newcsodvalue" ).val( "" );
						$( "#newcsodmeans" ).val( 0 );
						$( "#newcsodessential" ).prop( { checked: false } );
					}
				} else {
					if( 0 < data.msg.length ) $( "#ajax-response-csod" ).html( data.msg );
				}
			}).fail(function( msg ) {
				$( "#ajax-response-csod" ).html( msg );
				$( "#newcsod_loading" ).html( '' );
			});
			return false;
		},

		updOrder: function( key ) {
			var name = $( ':input[name="csod['+key+'][name]"]' ).val();
			var value = $( ':input[name="csod['+key+'][value]"]' ).val();
			var means = $( ':input[name="csod['+key+'][means]"]' ).val();
			var essential = ( $( ':input[name="csod['+key+'][essential]"]' ).prop( "checked" ) ) ? '1' : '0';
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
				$( "#ajax-response-csod" ).html( mes );
				return false;
			}

			$( "#csod_loading-"+key ).html( '<img src="'+uscesL10n.USCES_PLUGIN_URL+'/images/loading.gif" />' );

			var s = customField.settings;
			s.data = {
				action: 'custom_field_ajax',
				field: 'order',
				update: 1,
				key: key,
				name: name,
				value: value,
				means: means,
				essential: essential,
				wc_nonce: '<?php echo wp_create_nonce( 'custom_field_ajax' ); ?>'
			}
			s.dataType = 'json';
			$.ajax( s ).done(function( data ) {
				$( "#ajax-response-csod" ).html( '' );
				$( "#csod_loading-"+key ).html( '' );
				if( 'OK' == data.status ) {
					$( "tbody#csod-list" ).html( data.list );
					$( "#csod-"+key ).css( { 'background-color': '#FF4' } );
					$( "#csod-"+key ).animate( { 'background-color': '#FFFFEE' }, 2000 );
				} else {
					if( 0 < data.msg.length ) $( "#ajax-response-csod" ).html( data.msg );
				}
			}).fail(function( msg ) {
				$( "#ajax-response-csod" ).html( msg );
				$( "#csod_loading-"+key ).html( '' );
			});
			return false;
		},

		delOrder: function( key ) {
			$( "#csod-"+key ).css( { 'background-color': '#F00' } );
			$( "#csod-"+key ).animate( { 'background-color': '#FFFFEE' }, 1000 );
			var s = customField.settings;
			s.data = {
				action: 'custom_field_ajax',
				field: 'order',
				delete: 1,
				key: key,
				wc_nonce: '<?php echo wp_create_nonce( 'custom_field_ajax' ); ?>'
			}
			s.dataType = 'json';
			$.ajax( s ).done(function( data ) {
				$( "#ajax-response-csod" ).html( '' );
				if( 'OK' == data.status ) {
					$( "tbody#csod-list" ).html( data.list );
					if( data.list.length < 1 ) $( "table#csod-list-table" ).attr( "style", "display: none" );
				} else {
					if( 0 < data.msg.length ) $( "#ajax-response-csod" ).html( data.msg );
				}
			}).fail(function( msg ) {
				$( "#ajax-response-csod" ).html( msg );
			});
			return false;
		},

		//** Custom Customer **
		addCustomer: function() {
			var key = $( "#newcscskey" ).val();
			var name = $( "#newcscsname" ).val();
			var value = $( "#newcscsvalue" ).val();
			var means = $( "#newcscsmeans" ).val();
			var essential = ( $( "input#newcscsessential" ).prop( "checked" ) ) ? '1' : '0';
			var position = $( "#newcscsposition" ).val();
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
				$( "#ajax-response-cscs" ).html( mes );
				return false;
			}

			$( "#newcscs_loading" ).html( '<img src="'+uscesL10n.USCES_PLUGIN_URL+'/images/loading.gif" />' );

			var s = customField.settings;
			s.data = {
				action: 'custom_field_ajax',
				field: 'customer',
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
				$( "#ajax-response-cscs" ).html( '' );
				$( "#newcscs_loading" ).html( '' );
				if( 'OK' == data.status ) {
					if( 0 < data.dupkey ) {
						$( "#ajax-response-cscs" ).html( '<div class="error"><p>'+uscesL10n.message[23]+'</p></div>' );
					} else {
						if( data.list.length > 1 ) $( "table#cscs-list-table" ).removeAttr( "style" );
						$( "tbody#cscs-list" ).html( data.list );
						$( "#cscs-"+key ).css( { 'background-color': '#FF4' } );
						$( "#cscs-"+key ).animate( { 'background-color': '#FFFFEE' }, 2000 );
						$( "#newcscskey" ).val( "" );
						$( "#newcscsname" ).val( "" );
						$( "#newcscsvalue" ).val( "" );
						$( "#newcscsmeans" ).val( 0 );
						$( "#newcscsessential" ).prop( { checked: false } );
					}
				} else {
					if( 0 < data.msg.length ) $( "#ajax-response-cscs" ).html( data.msg );
				}
			}).fail(function( msg ) {
				$( "#ajax-response-cscs" ).html( msg );
				$( "#newcscs_loading" ).html( '' );
			});
			return false;
		},

		updCustomer: function(key) {
			var name = $( ':input[name="cscs['+key+'][name]"]' ).val();
			var value = $( ':input[name="cscs['+key+'][value]"]' ).val();
			var means = $( ':input[name="cscs['+key+'][means]"]' ).val();
			var essential = ( $( ':input[name="cscs['+key+'][essential]"]' ).prop( "checked" ) ) ? '1' : '0';
			var position = $( ':input[name="cscs['+key+'][position]"]' ).val();
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
				$("#ajax-response-cscs").html( mes );
				return false;
			}

			$( "#cscs_loading-"+key ).html( '<img src="'+uscesL10n.USCES_PLUGIN_URL+'/images/loading.gif" />' );

			var s = customField.settings;
			s.data = {
				action: 'custom_field_ajax',
				field: 'customer',
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
				$( "#ajax-response-cscs" ).html( '' );
				$( "#cscs_loading-"+key ).html( '' );
				if( 'OK' == data.status ) {
					$( "tbody#cscs-list" ).html( data.list );
					$( "#cscs-"+key ).css( { 'background-color': '#FF4' } );
					$( "#cscs-"+key ).animate( { 'background-color': '#FFFFEE' }, 2000 );
				} else {
					if( 0 < data.msg.length ) $( "#ajax-response-cscs" ).html( data.msg );
				}
			}).fail(function( msg ) {
				$( "#ajax-response-cscs" ).html( msg );
				$( "#cscs_loading-"+key ).html( '' );
			});
			return false;
		},

		delCustomer: function( key ) {
			$( "#cscs-"+key ).css( { 'background-color': '#F00' } );
			$( "#cscs-"+key ).animate( { 'background-color': '#FFFFEE' }, 1000 );
			var s = customField.settings;
			s.data = {
				action: 'custom_field_ajax',
				field: 'customer',
				delete: 1,
				key: key,
				wc_nonce: '<?php echo wp_create_nonce( 'custom_field_ajax' ); ?>'
			}
			s.dataType = 'json';
			$.ajax(s).done(function( data ) {
				$( "#ajax-response-cscs" ).html( '' );
				if( 'OK' == data.status ) {
					$( "tbody#cscs-list" ).html( data.list );
					if( data.list.length < 1 ) $( "table#cscs-list-table" ).attr( "style", "display: none" );
				} else {
					if( 0 < data.msg.length ) $( "#ajax-response-csmb" ).html( data.msg );
				}
			}).fail(function( msg ) {
				$( "#ajax-response-cscs" ).html( msg );
			});
			return false;
		},

		//** Custom Delivery **
		addDelivery: function() {
			var key = $( "#newcsdekey" ).val();
			var name = $( "#newcsdename" ).val();
			var value = $( "#newcsdevalue" ).val();
			var means = $( "#newcsdemeans" ).val();
			var essential = ( $( "input#newcsdeessential" ).prop( "checked" ) ) ? '1' : '0';
			var position = $( "#newcsdeposition" ).val();
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
				$("#ajax-response-csde").html( mes );
				return false;
			}

			$( "#newcsde_loading" ).html( '<img src="'+uscesL10n.USCES_PLUGIN_URL+'/images/loading.gif" />' );

			var s = customField.settings;
			s.data = {
				action: 'custom_field_ajax',
				field: 'delivery',
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
				$( "#ajax-response-csde" ).html( '' );
				$( "#newcsde_loading" ).html( '' );
				if( 'OK' == data.status ) {
					if( 0 < data.dupkey ) {
						$( "#ajax-response-csde" ).html( '<div class="error"><p>'+uscesL10n.message[23]+'</p></div>' );
					} else {
						if( data.list.length > 1 ) $( "table#csde-list-table" ).removeAttr( "style" );
						$( "tbody#csde-list" ).html( data.list );
						$( "#csde-"+key ).css( { 'background-color': '#FF4' } );
						$( "#csde-"+key ).animate( { 'background-color': '#FFFFEE' }, 2000 );
						$( "#newcsdekey" ).val( "" );
						$( "#newcsdename" ).val( "" );
						$( "#newcsdevalue" ).val( "" );
						$( "#newcsdemeans" ).val( 0 );
						$( "#newcsdeessential" ).prop( { checked: false } );
					}
				} else {
					if( 0 < data.msg.length ) $( "#ajax-response-csde" ).html( data.msg );
				}
			}).fail(function( msg ) {
				$( "#ajax-response-csde" ).html( msg );
				$( "#newcsde_loading" ).html( '' );
			});
			return false;
		},

		updDelivery: function( key ) {
			var name = $( ':input[name="csde['+key+'][name]"]' ).val();
			var value = $( ':input[name="csde['+key+'][value]"]' ).val();
			var means = $( ':input[name="csde['+key+'][means]"]' ).val();
			var essential = ( $( ':input[name="csde['+key+'][essential]"]' ).prop( "checked" ) ) ? '1' : '0';
			var position = $( ':input[name="csde['+key+'][position]"]' ).val();
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
				$( "#ajax-response-csde" ).html( mes );
				return false;
			}

			$( "#csde_loading-"+key ).html( '<img src="'+uscesL10n.USCES_PLUGIN_URL+'/images/loading.gif" />' );

			var s = customField.settings;
			s.data = {
				action: 'custom_field_ajax',
				field: 'delivery',
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
				$( "#ajax-response-csde" ).html( '' );
				$( "#csde_loading-"+key ).html( '' );
				if( 'OK' == data.status ) {
					$( "tbody#csde-list" ).html( data.list );
					$( "#csde-"+key ).css( { 'background-color': '#FF4' } );
					$( "#csde-"+key ).animate( { 'background-color': '#FFFFEE' }, 2000 );
				} else {
					if( 0 < data.msg.length ) $( "#ajax-response-csde" ).html( data.msg );
				}
			}).fail(function( msg ) {
				$( "#ajax-response-csde" ).html( msg );
				$( "#csde_loading-"+key ).html( '' );
			});
			return false;
		},

		delDelivery: function( key ) {
			$( "#csde-"+key ).css( { 'background-color': '#F00' } );
			$( "#csde-"+key ).animate( { 'background-color': '#FFFFEE' }, 1000 );
			var s = customField.settings;
			s.data = {
				action: 'custom_field_ajax',
				field: 'delivery',
				delete: 1,
				key: key,
				wc_nonce: '<?php echo wp_create_nonce( 'custom_field_ajax' ); ?>'
			}
			s.dataType = 'json';
			$.ajax( s ).done(function( data ) {
				$( "#ajax-response-csde" ).html( '' );
				if( 'OK' == data.status ) {
					$( "tbody#csde-list" ).html( data.list );
					if( data.list.length < 1 ) $( "table#csde-list-table" ).attr( "style", "display: none" );
				} else {
					if( 0 < data.msg.length ) $( "#ajax-response-csde" ).html( data.msg );
				}
			}).fail(function( msg ) {
				$( "#ajax-response-csde" ).html( msg );
			});
			return false;
		}
	};
});
</script>
<div class="wrap">
<div class="usces_admin">
<h1>Welcart Shop <?php esc_html_e( 'Cart Page Setting', 'usces' ); ?></h1>
<?php usces_admin_action_status(); ?>
<form action="" method="post" name="option_form" id="option_form">
<input name="usces_option_update" type="submit" class="button button-primary" value="<?php esc_attr_e( 'change decision', 'usces' ); ?>" />
<div id="poststuff" class="metabox-holder">
<div class="uscestabs" id="uscestabs_cart">
<ul>
	<li><a href="#cart_page_setting_1"><?php esc_html_e( 'Rule of the column for a item name', 'usces' ); ?></a></li>
	<li><a href="#cart_page_setting_2"><?php esc_html_e( 'Explanation in a Cart page', 'usces' ); ?></a></li>
	<li><a href="#cart_page_setting_6"><?php esc_html_e( 'Description to be displayed on the confirmation page', 'usces' ); ?></a></li>
	<li><a href="#cart_page_setting_3"><?php esc_html_e( 'Custom order field', 'usces' ); ?></a></li>
	<li><a href="#cart_page_setting_4"><?php esc_html_e( 'Custom customer field', 'usces' ); ?></a></li>
	<li><a href="#cart_page_setting_5"><?php esc_html_e( 'Custom delivery field', 'usces' ); ?></a></li>
	<?php do_action( 'usces_action_admin_cart_tab_label' ); ?>
</ul>

<div id="cart_page_setting_1">
	<div class="postbox">
	<h3><span><?php esc_html_e( 'Rule of the column for a item name', 'usces' ); ?></span><a style="cursor:pointer;" onclick="toggleVisibility('ex_item_indication');"><?php esc_html_e( '(Explain)', 'usces' ); ?></a></h3>
	<div class="inside">
	<table class="form_table">
		<tr>
			<th><label for="indi_item_name"><?php esc_html_e( 'Indication of item name', 'usces' ); ?></label></th>
			<td><input name="indication[item_name]" type="checkbox" id="indi_item_name" value="<?php echo esc_attr( $indi_item_name['item_name'] ); ?>"<?php echo esc_attr( $checked_item_name['item_name'] ); ?> /></td>
			<th><?php esc_html_e( 'Position of item name', 'usces' ); ?></th>
			<td><input name="position[item_name]" type="text" class="num" id="pos_item_name" value="<?php echo esc_attr( $pos_item_name['item_name'] ); ?>" />(<?php esc_html_e( 'numeric', 'usces' ); ?>)</td>
		</tr>
		<tr>
			<th><label for="indi_item_code"><?php esc_html_e( 'Indication of item code', 'usces' ); ?></label></th>
			<td><input name="indication[item_code]" type="checkbox" id="indi_item_code" value="<?php echo esc_attr( $indi_item_name['item_code'] ); ?>"<?php echo esc_attr( $checked_item_name['item_code'] ); ?> /></td>
			<th><?php esc_html_e( 'Position of item code', 'usces' ); ?></th>
			<td><input name="position[item_code]" type="text" class="num" id="pos_item_code" value="<?php echo esc_attr( $pos_item_name['item_code'] ); ?>" />(<?php esc_html_e( 'numeric', 'usces' ); ?>)</td>
		</tr>
		<tr>
			<th><label for="indi_sku_name"><?php esc_html_e( 'Indication of SKU name', 'usces' ); ?></label></th>
			<td><input name="indication[sku_name]" type="checkbox" id="indi_sku_name" value="<?php echo esc_attr( $indi_item_name['sku_name'] ); ?>"<?php echo esc_attr( $checked_item_name['sku_name'] ); ?> /></td>
			<th><?php esc_html_e( 'Position of SKU name', 'usces' ); ?></th>
			<td><input name="position[sku_name]" type="text" class="num" id="pos_sku_name" value="<?php echo esc_attr( $pos_item_name['sku_name'] ); ?>" />(<?php esc_html_e( 'numeric', 'usces' ); ?>)</td>
		</tr>
		<tr>
			<th><label for="indi_sku_code"><?php esc_html_e( 'Indication of SKU code', 'usces' ); ?></label></th>
			<td><input name="indication[sku_code]" type="checkbox" id="indi_sku_code" value="<?php echo esc_attr( $indi_item_name['sku_code'] ); ?>"<?php echo esc_attr( $checked_item_name['sku_code'] ); ?> /></td>
			<th><?php esc_html_e( 'Position of SKU code', 'usces' ); ?></th>
			<td><input name="position[sku_code]" type="text" class="num" id="pos_sku_code" value="<?php echo esc_attr( $pos_item_name['sku_code'] ); ?>" />(<?php esc_html_e( 'numeric', 'usces' ); ?>)</td>
		</tr>
	</table>
	<hr size="1" color="#CCCCCC" />
	<div id="ex_item_indication" class="explanation"><?php _e( 'You can appoint indication, non-indication, sort of the item name to show the cart.<br />This rule is applied as brand names such as a cart page, contents confirmation page, a member information purchase history, an email, a written estimate, the statement of delivery.', 'usces' ); ?></div>
	</div>
	</div><!--postbox-->
</div><!--cart_page_setting_1-->

<div id="cart_page_setting_2">
	<div class="postbox">
	<h3><span><?php esc_html_e( 'Explanation in a Cart page', 'usces' ); ?></span><a style="cursor:pointer;" onclick="toggleVisibility('ex_cart_page');"><?php esc_html_e( '(Explain)', 'usces' ); ?></a></h3>
	<div class="inside">
	<table class="form_table">
		<tr>
			<th><?php esc_html_e( 'header', 'usces' ); ?></th>
			<td><textarea name="header[cart]" id="header[cart]" class="textarea_fld"><?php wel_esc_script_e( $cart_header ); ?></textarea></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<th><?php esc_html_e( 'footer', 'usces' ); ?></th>
			<td><textarea name="footer[cart]" id="footer[cart]" class="textarea_fld"><?php wel_esc_script_e( $cart_footer ); ?></textarea></td>
			<td>&nbsp;</td>
		</tr>
	</table>
	<hr size="1" color="#CCCCCC" />
	<div id="ex_cart_page" class="explanation"><?php esc_html_e( 'You can set additional explanation to insert in a cart page.', 'usces' ); ?></div>
	</div>
	</div><!--postbox-->

	<div class="postbox">
	<h3><span><?php esc_html_e( 'Explanation in a Customer Info page', 'usces' ); ?></span><a style="cursor:pointer;" onclick="toggleVisibility('ex_customer_page');"><?php esc_html_e( '(Explain)', 'usces' ); ?></a></h3>
	<div class="inside">
	<table class="form_table">
		<tr>
			<th><?php esc_html_e( 'header', 'usces' ); ?></th>
			<td><textarea name="header[customer]" id="header[customer]" class="textarea_fld"><?php wel_esc_script_e( $customer_header ); ?></textarea></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<th><?php esc_html_e( 'footer', 'usces' ); ?></th>
			<td><textarea name="footer[customer]" id="footer[customer]" class="textarea_fld"><?php wel_esc_script_e( $customer_footer ); ?></textarea></td>
			<td>&nbsp;</td>
		</tr>
	</table>
	<hr size="1" color="#CCCCCC" />
	<div id="ex_customer_page" class="explanation"><?php esc_html_e( 'You can set additional explanation to insert in a customer information page.', 'usces' ); ?></div>
	</div>
	</div><!--postbox-->

	<div class="postbox">
	<h3><span><?php esc_html_e( 'Explanation in Delivery and Payment method page', 'usces' ); ?></span><a style="cursor:pointer;" onclick="toggleVisibility('ex_delivery_page');"><?php esc_html_e( '(Explain)', 'usces' ); ?></a></h3>
	<div class="inside">
	<table class="form_table">
		<tr>
			<th><?php esc_html_e( 'header', 'usces' ); ?></th>
			<td><textarea name="header[delivery]" id="header[delivery]" class="textarea_fld"><?php wel_esc_script_e( $delivery_header ); ?></textarea></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<th><?php esc_html_e( 'footer', 'usces' ); ?></th>
			<td><textarea name="footer[delivery]" id="footer[delivery]" class="textarea_fld"><?php wel_esc_script_e( $delivery_footer ); ?></textarea></td>
			<td>&nbsp;</td>
		</tr>
	</table>
	<hr size="1" color="#CCCCCC" />
	<div id="ex_delivery_page" class="explanation"><?php esc_html_e( 'You can set additional explanation to insert in a delivery and a payment method page.', 'usces' ); ?></div>
	</div>
	</div><!--postbox-->

	<div class="postbox">
	<h3><span><?php esc_html_e( 'Explanation in a Confirm page', 'usces' ); ?></span><a style="cursor:pointer;" onclick="toggleVisibility('ex_confirm_page');"><?php esc_html_e( '(Explain)', 'usces' ); ?></a></h3>
	<div class="inside">
	<table class="form_table">
		<tr>
			<th><?php esc_html_e( 'header', 'usces' ); ?></th>
			<td><textarea name="header[confirm]" id="header[confirm]" class="textarea_fld"><?php wel_esc_script_e( $confirm_header ); ?></textarea></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<th><?php esc_html_e( 'footer', 'usces' ); ?></th>
			<td><textarea name="footer[confirm]" id="footer[confirm]" class="textarea_fld"><?php wel_esc_script_e( $confirm_footer ); ?></textarea></td>
			<td>&nbsp;</td>
		</tr>
	</table>
	<hr size="1" color="#CCCCCC" />
	<div id="ex_confirm_page" class="explanation"><?php esc_html_e( 'You can set additional explanation to insert in a confirm page.', 'usces' ); ?></div>
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
</div><!--cart_page_setting_2-->

<div id="cart_page_setting_6">
	<div class="postbox">
	<h3><span><?php esc_html_e( 'Matters Concerning Withdrawal or Cancellation of Application', 'usces' ); ?></span><a style="cursor:pointer;" onclick="toggleVisibility('ex_confirm_notes');"><?php esc_html_e( '(Explain)', 'usces' ); ?></a></h3>
	<div class="inside">
	<textarea name="confirm_notes" rows="10" cols="50" id="confirm_notes" class="large-text"><?php wel_esc_script_e( $confirm_notes ); ?></textarea>
	<hr size="1" color="#CCCCCC" />
	<div id="ex_confirm_notes" class="explanation"><?php esc_html_e( 'Information on how to contact the company for returns and cancellations, contact information, and conditions for returns and cancellations can be displayed on the confirmation page.', 'usces' ); ?></div>
	</div>
	</div><!--postbox-->
</div><!--cart_page_setting_1-->
<?php
$csod_meta        = usces_has_custom_field_meta( 'order' );
$csod_display     = ( empty( $csod_meta ) ) ? ' style="display: none;"' : '';
$csod_means       = get_option( 'usces_custom_order_select' );
$csod_meansoption = '';
foreach ( $csod_means as $meankey => $meanvalue ) {
	$csod_meansoption .= '<option value="' . $meankey . '">' . $meanvalue . "</option>\n";
}
?>
<div id="cart_page_setting_3">
	<div class="postbox">
	<h3><span><?php esc_html_e( 'Custom order field', 'usces' ); ?><a style="cursor:pointer;" onclick="toggleVisibility('ex_custom_order');"><?php esc_html_e( '(Explain)', 'usces' ); ?></a></span></h3>
	<div class="inside">
	<div id="postoptcustomstuff">
	<table id="csod-list-table" class="list"<?php echo esc_attr( $csod_display ); ?>>
		<thead>
		<tr>
		<th class="left"><?php esc_html_e( 'key name', 'usces' ); ?></th>
		<th rowspan="2"><?php esc_html_e( 'selected amount', 'usces' ); ?></th>
		</tr>
		<tr>
		<th class="left"><?php esc_html_e( 'field name', 'usces' ); ?></th>
		</tr>
		</thead>
		<tbody id="csod-list">
<?php
if ( is_array( $csod_meta ) ) {
	foreach ( $csod_meta as $key => $entry ) {
		echo _list_custom_order_meta_row( $key, $entry );
	}
}
?>
		</tbody>
	</table>
	<div id="ajax-response-csod"></div>
	<p><strong><?php esc_html_e( 'Add a new custom order field', 'usces' ); ?> : </strong></p>
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
		<input type="text" name="newcsodkey" id="newcsodkey" class="optname" value="" />
		<input type="text" name="newcsodname" id="newcsodname" class="optname" value="" />
		<div class="optcheck"><select name="newcsodmeans" id="newcsodmeans"><?php wel_esc_script_e( $csod_meansoption ); ?></select>
		<input type="checkbox" name="newcsodessential" id="newcsodessential" /><label for="newcsodessential"><?php esc_html_e( 'Required', 'usces' ); ?></label></div>
		</td>
		<td class="item-opt-value"><textarea name="newcsodvalue" id="newcsodvalue" class="optvalue"></textarea></td>
		</tr>

		<tr><td colspan="2" class="submit">
		<input type="button" class="button" name="add_csod" id="add_csod" value="<?php esc_attr_e( 'Add custom order field', 'usces' ); ?>" onclick="customField.addOrder();" />
		<div id="newcsod_loading" class="meta_submit_loading"></div>
		</td></tr>
		</tbody>
	</table>

	<hr size="1" color="#CCCCCC" />
	<div id="ex_custom_order" class="explanation"><?php esc_html_e( 'You can add an arbitrary field to the page of the payment method.', 'usces' ); ?></div>
	</div>
	</div>
	</div><!--postbox-->
</div><!--cart_page_setting_3-->
<?php
$cscs_meta        = usces_has_custom_field_meta( 'customer' );
$cscs_display     = ( empty( $cscs_meta ) ) ? ' style="display: none;"' : '';
$cscs_means       = get_option( 'usces_custom_customer_select' );
$cscs_meansoption = '';
foreach ( $cscs_means as $meankey => $meanvalue ) {
	$cscs_meansoption .= '<option value="' . $meankey . '">' . $meanvalue . "</option>\n";
}
$positions       = get_option( 'usces_custom_field_position_select' );
$positionsoption = '';
foreach ( $positions as $poskey => $posvalue ) {
	$positionsoption .= '<option value="' . $poskey . '">' . $posvalue . "</option>\n";
}
?>
<div id="cart_page_setting_4">
	<div class="postbox">
	<h3><span><?php esc_html_e( 'Custom customer field', 'usces' ); ?><a style="cursor:pointer;" onclick="toggleVisibility('ex_custom_customer');"><?php esc_html_e( '(Explain)', 'usces' ); ?></a></span></h3>
	<div class="inside">
	<div id="postoptcustomstuff">
	<table id="cscs-list-table" class="list"<?php echo esc_attr( $cscs_display ); ?>>
		<thead>
		<tr>
		<th class="left"><?php esc_html_e( 'key name', 'usces' ); ?></th>
		<th rowspan="2"><?php esc_html_e( 'selected amount', 'usces' ); ?></th>
		</tr>
		<tr>
		<th class="left"><?php esc_html_e( 'field name', 'usces' ); ?></th>
		</tr>
		</thead>
		<tbody id="cscs-list">
<?php
if ( is_array( $cscs_meta ) ) {
	foreach ( $cscs_meta as $key => $entry ) {
		echo _list_custom_customer_meta_row( $key, $entry );
	}
}
?>
		</tbody>
	</table>
	<div id="ajax-response-cscs"></div>
	<p><strong><?php esc_html_e( 'Add a new custom customer field', 'usces' ); ?> : </strong></p>
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
		<input type="text" name="newcscskey" id="newcscskey" class="optname" value="" />
		<input type="text" name="newcscsname" id="newcscsname" class="optname" value="" />
		<div class="optcheck"><select name="newcscsmeans" id="newcscsmeans"><?php wel_esc_script_e( $cscs_meansoption ); ?></select>
		<input type="checkbox" name="newcscsessential" id="newcscsessential" /><label for="newcscsessential"><?php esc_html_e( 'Required', 'usces' ); ?></label>
		<select name="newcscsposition" id="newcscsposition"><?php wel_esc_script_e( $positionsoption ); ?></select></div>
		</td>
		<td class="item-opt-value"><textarea name="newcscsvalue" id="newcscsvalue" class="optvalue"></textarea></td>
		</tr>

		<tr><td colspan="2" class="submit">
		<input type="button" class="button" name="add_cscs" id="add_cscs" value="<?php esc_attr_e( 'Add custom customer field', 'usces' ); ?>" onclick="customField.addCustomer();" />
		<div id="newcscs_loading" class="meta_submit_loading"></div>
		</td></tr>
		</tbody>
	</table>

	<hr size="1" color="#CCCCCC" />
	<div id="ex_custom_customer" class="explanation"><?php esc_html_e( 'You can add an arbitrary field to customer information.', 'usces' ); ?></div>
	</div>
	</div>
	</div><!--postbox-->
</div><!--cart_page_setting_4-->
<?php
$csde_meta        = usces_has_custom_field_meta( 'delivery' );
$csde_display     = ( empty( $csde_meta ) ) ? ' style="display: none;"' : '';
$csde_means       = get_option( 'usces_custom_delivery_select' );
$csde_meansoption = '';
foreach ( $csde_means as $meankey => $meanvalue ) {
	$csde_meansoption .= '<option value="' . $meankey . '">' . $meanvalue . "</option>\n";
}
?>
<div id="cart_page_setting_5">
	<div class="postbox">
	<h3><span><?php esc_html_e( 'Custom delivery field', 'usces' ); ?><a style="cursor:pointer;" onclick="toggleVisibility('ex_custom_delivery');"><?php esc_html_e( '(Explain)', 'usces' ); ?></a></span></h3>
	<div class="inside">
	<div id="postoptcustomstuff">
	<table id="csde-list-table" class="list"<?php echo esc_attr( $csde_display ); ?>>
		<thead>
		<tr>
		<th class="left"><?php esc_html_e( 'key name', 'usces' ); ?></th>
		<th rowspan="2"><?php esc_html_e( 'selected amount', 'usces' ); ?></th>
		</tr>
		<tr>
		<th class="left"><?php esc_html_e( 'field name', 'usces' ); ?></th>
		</tr>
		</thead>
		<tbody id="csde-list">
<?php
if ( is_array( $csde_meta ) ) {
	foreach ( $csde_meta as $key => $entry ) {
		echo _list_custom_delivery_meta_row( $key, $entry );
	}
}
?>
		</tbody>
	</table>
	<div id="ajax-response-csde"></div>
	<p><strong><?php esc_html_e( 'Add a new custom delivery field', 'usces' ); ?> : </strong></p>
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
		<input type="text" name="newcsdekey" id="newcsdekey" class="optname" value="" />
		<input type="text" name="newcsdename" id="newcsdename" class="optname" value="" />
		<div class="optcheck"><select name="newcsdemeans" id="newcsdemeans"><?php wel_esc_script_e( $csde_meansoption ); ?></select>
		<input type="checkbox" name="newcsdeessential" id="newcsdeessential" /><label for="newcsdeessential"><?php esc_html_e( 'Required', 'usces' ); ?></label>
		<select name="newcsdeposition" id="newcsdeposition"><?php wel_esc_script_e( $positionsoption ); ?></select></div>
		</td>
		<td class="item-opt-value"><textarea name="newcsdevalue" id="newcsdevalue" class="optvalue"></textarea></td>
		</tr>

		<tr><td colspan="2" class="submit">
		<input type="button" class="button" name="add_csde" id="add_csde" value="<?php esc_attr_e( 'Add custom delivery field', 'usces' ); ?>" onclick="customField.addDelivery();" />
		<div id="newcsde_loading" class="meta_submit_loading"></div>
		</td></tr>
		</tbody>
	</table>

	<hr size="1" color="#CCCCCC" />
	<div id="ex_custom_delivery" class="explanation"><?php esc_html_e( 'You can add an arbitrary field to delivery information.', 'usces' ); ?></div>
	</div>
	</div>
	</div><!--postbox-->
</div><!--cart_page_setting_5-->
<?php do_action( 'usces_action_admin_cart_tab_body' ); ?>
</div><!--uscestabs_cart-->
</div><!--poststuff-->
<input name="usces_option_update" type="submit" class="button button-primary" value="<?php esc_attr_e( 'change decision', 'usces' ); ?>" />
<?php wp_nonce_field( 'admin_cart', 'wc_nonce' ); ?>
</form>
</div><!--usces_admin-->
</div><!--wrap-->
