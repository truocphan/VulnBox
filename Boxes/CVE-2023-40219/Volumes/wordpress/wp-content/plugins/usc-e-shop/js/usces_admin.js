// JavaScript
(function($) {
	$("#aAdditionalURLs").click(function () {
		$("#AdditionalURLs").toggle();
	});

	itemOpt = {
		settings: {
			url: uscesL10n.requestFile,
			type: 'POST',
			cache: false
		},

		post : function(action, arg) {
			if( action == 'updateitemopt' ) {
				itemOpt.updateitemopt(arg);
			} else if( action == 'deleteitemopt' ) {
				itemOpt.deleteitemopt(arg);
			} else if( action == 'additemopt' ) {
				itemOpt.additemopt();
			} else if( action == 'addcommonopt' ) {
				itemOpt.addcommonopt();
			} else if( action == 'keyselect' ) {
				itemOpt.keyselect(arg);
			}
		},

		additemopt : function() {
			if($("#optkeyselect").val() == "#NONE#") return;

			var id = $("#post_ID").val();
			var name = $("#optkeyselect option:selected").html();
			var value = $("#newoptvalue").val();
			var means = $("#newoptmeans").val();
	
			var wc_nonce = $("#wc_nonce").val();
			var _wp_http_referer = $("input[name='_wp_http_referer']").val();

			if($("input#newoptessential").prop("checked")){
				var essential = '1';
			}else{
				var essential = '0';
			}

			var mes = '';
			if( '' == name ){
				mes += '<p>'+uscesL10n.message[0]+'</p>';
			} else {
				var check = true;
				$("input[name*='\[name\]']").each(function(){ if( name == $(this).val() ){ check = false; }});
				if( !check ){
					mes += '<p>'+uscesL10n.message[1]+'</p>';
				}
			}
			if( '' == value && (0 == means || 1 == means || 3 == means || 4 == means) ){
				mes += '<p>'+uscesL10n.message[2]+'</p>';
			}else if( '' != value && (2 == means || 5 == means) ){
				mes += '<p>'+uscesL10n.message[3]+'</p>';
			}
			if( '' != mes ){
				$("#itemopt_ajax-response").html('<div class="error">' + mes + '</div>');
				return false;
			}

			$("#newitemopt_loading").html('<img src="' + uscesL10n.USCES_PLUGIN_URL + '/images/loading.gif" />');

			var s = itemOpt.settings;
			s.data = "action=item_option_ajax&ID=" + id + "&newoptname=" + encodeURIComponent(name) + "&newoptvalue=" + encodeURIComponent(value) + "&newoptmeans=" + encodeURIComponent(means) + "&newoptessential=" + encodeURIComponent(essential) + "&wc_nonce=" + encodeURIComponent(wc_nonce)+ "&_wp_http_referer=" + encodeURIComponent(_wp_http_referer);
			$.ajax( s ).done(function( data ){
				console.log(data);
				var meta_id = data.meta_id;
				$("#itemopt_ajax-response").html('');
				$("#newitemopt_loading").html('');
				$("table#optlist-table").removeAttr("style");
				$("tbody#item-opt-list").html( data.meta_row );
				$("#optkeyselect").val('#NONE#');
				$("#newoptvalue").html('');
				$("#newoptmeans").val(0);
				$("#newoptessential").prop( "checked", false );
				$("#itemopt-" + meta_id).css({'background-color': '#FF4'});
				$("#itemopt-" + meta_id).animate({ 'background-color': '#FFFFEE' }, 2000 );
			}).fail(function( msg ){
				$("#itemopt_ajax-response").html(msg);
				$("#newitemopt_loading").html('');
			});
			return false;
		},

		addcommonopt : function() {
			var id = $("#post_ID").val();
			var name = $("#newoptname").val();
			var value = $("#newoptvalue").val();
			var means = $("#newoptmeans").val();
	
			var wc_nonce = $("#wc_nonce").val();
			var _wp_http_referer = $("input[name='_wp_http_referer']").val();

			if($("input#newoptessential").prop("checked")){
				var essential = '1';
			}else{
				var essential = '0';
			}

			var mes = '';
			if( '' == name ){
				mes += '<p>'+uscesL10n.message[0]+'</p>';
			} else {
				var check = true;
				$("input[name*='\[name\]']").each(function(){ if( name == $(this).val() ){ check = false; }});
				if( !check ){
					mes += '<p>'+uscesL10n.message[1]+'</p>';
				}
			}
			if( '' == value && (0 == means || 1 == means || 3 == means || 4 == means) ){
				mes += '<p>'+uscesL10n.message[2]+'</p>';
			}else if( '' != value && (2 == means || 5 == means) ){
				mes += '<p>'+uscesL10n.message[3]+'</p>';
			}
			if( '' != mes ){
				$("#itemopt_ajax-response").html('<div class="error">' + mes + '</div>');
				return false;
			}

			$("#newcomopt_loading").html('<img src="' + uscesL10n.USCES_PLUGIN_URL + '/images/loading.gif" />');

			var s = itemOpt.settings;
			s.data = "action=item_option_ajax&ID=" + id + "&newoptname=" + encodeURIComponent(name) + "&newoptvalue=" +encodeURIComponent(value) + "&newoptmeans=" + encodeURIComponent(means) + "&newoptessential=" + encodeURIComponent(essential) + "&wc_nonce=" + encodeURIComponent(wc_nonce)+ "&_wp_http_referer=" + encodeURIComponent(_wp_http_referer);
			$.ajax( s ).done(function( data ){
				console.log(data);
				var meta_id = data.meta_id;
				$("#newcomopt_loading").html('');
				$("#itemopt_ajax-response").html('');
				$("table#optlist-table").removeAttr("style");
				if( 0 > meta_id ){
					$("#itemopt_ajax-response").html('<div class="error"><p>'+uscesL10n.message[1]+'</p></div>');
				}else{
					$("tbody#item-opt-list").html( data.meta_row );
					$("#newoptname").val('');
					$("#newoptvalue").val('');
					$("#newoptmeans").val(0);
					$("#newoptessential").prop( "checked", false );
					$("#itemopt-" + meta_id).css({'background-color': '#FF4'});
					$("#itemopt-" + meta_id).animate({ 'background-color': '#FFFFEE' }, 2000 );
				}
			}).fail(function(msg){
				$("#comopt_ajax-response").html(msg);
				$("#newcomopt_loading").html('');
			});
			return false;
		},

		updateitemopt : function(meta_id) {
			var id = $("#post_ID").val();
	
			var wc_nonce = $("#wc_nonce").val();
			var _wp_http_referer = $("input[name='_wp_http_referer']").val();

			nm = document.getElementById('itemopt\['+meta_id+'\]\[name\]');
			vs = document.getElementById('itemopt\['+meta_id+'\]\[value\]');
			ms = document.getElementById('itemopt\['+meta_id+'\]\[means\]');
			es = document.getElementById('itemopt\['+meta_id+'\]\[essential\]');
			so = document.getElementById('itemopt\['+meta_id+'\]\[sort\]');
			var name = $(nm).val();
			var value = uscesItem.trim($(vs).val());
			var means = $(ms).val();
			var sortnum = $(so).val();
			if($(es).prop("checked")){
				var essential = '1';
			}else{
				var essential = '0';
			}

			var mes = '';
			if( '' == name ){
				mes += '<p>'+uscesL10n.message[0]+'</p>';
			}
			if( '' == value && (0 == means || 1 == means || 3 == means || 4 == means) ){
				mes += '<p>'+uscesL10n.message[2]+'</p>';
			}else if( '' != value && (2 == means || 5 == means) ){
				mes += '<p>'+uscesL10n.message[3]+'</p>';
			}
			if( '' != mes ){
				$("#itemopt_ajax-response").html('<div class="error">' + mes + '</div>');
				return false;
			}

			$("#itemopt_loading-" + meta_id).html('<img src="' + uscesL10n.USCES_PLUGIN_URL + '/images/loading.gif" />');

			var s = itemOpt.settings;
			s.data = "action=item_option_ajax&ID=" + id + "&update=1&optname=" + encodeURIComponent(name) + "&optvalue=" + encodeURIComponent(value) + "&optmeans=" + means + "&optessential=" + essential + "&sort=" + sortnum + "&optmetaid=" + meta_id + "&wc_nonce=" + encodeURIComponent(wc_nonce)+ "&_wp_http_referer=" + encodeURIComponent(_wp_http_referer);
			$.ajax( s ).done(function( data ){
				console.log(data);
				$("#itemopt_ajax-response").html('');
				$("#itemopt_loading-" + meta_id).html('');
				$("tbody#item-opt-list").html( data.meta_row );
				$("#itemopt-" + meta_id).css({'background-color': '#FF4'});
				$("#itemopt-" + meta_id).animate({ 'background-color': '#FFFFEE' }, 2000 );
			}).fail(function( msg ){
				$("#itemopt_ajax-response").html(msg);
				$("#itemopt_loading-" + meta_id).html('');
			});
			return false;
		},

		deleteitemopt : function(meta_id) {
			$("#itemopt-" + meta_id).css({'background-color': '#F00'});
			$("#itemopt-" + meta_id).animate({ 'background-color': '#FFFFEE' }, 1000 );
			var id = $("#post_ID").val();
	
			var wc_nonce = $("#wc_nonce").val();
			var _wp_http_referer = $("input[name='_wp_http_referer']").val();

			var s = itemOpt.settings;
			s.data = "action=item_option_ajax&ID=" + id + "&delete=1&optmetaid=" + meta_id + "&wc_nonce=" + encodeURIComponent(wc_nonce)+ "&_wp_http_referer=" + encodeURIComponent(_wp_http_referer);
			$.ajax( s ).done(function( data ){
				console.log(data);
				$("#itemopt_ajax-response").html("");
				$("tbody#item-opt-list").html( data.meta_row );
			}).fail(function( msg ){ });
			return false;
		},

		keyselect : function( meta_id ) {
			if(meta_id == '#NONE#'){
				$("#newoptvalue").val('');
				$("#newoptmeans").val(0);
				$("#newoptessential").prop( "checked", false );
				return;
			}
			var id = uscesL10n.cart_number;
	
			var wc_nonce = $("#wc_nonce").val();
			var _wp_http_referer = $("input[name='_wp_http_referer']").val();

			$("#newitemopt_loading").html('<img src="' + uscesL10n.USCES_PLUGIN_URL + '/images/loading.gif" />');
			$("#add_itemopt").prop( "disabled", true );

			var s = itemOpt.settings;
			s.data = "action=item_option_ajax&ID=" + id + "&select=1&meta_id=" + meta_id + "&wc_nonce=" + encodeURIComponent(wc_nonce)+ "&_wp_http_referer=" + encodeURIComponent(_wp_http_referer);
			$.ajax( s ).done(function( data ){
				console.log(data);
				$("#itemopt_ajax-response").html("");
				var means = data.means;
				var essential = data.essential;
				var value = ( 2 == means || 5 == means ) ? '' : data.value;
				value = esc_js( String( value ) );
				$("#newoptvalue").html(value);
				$("#newoptmeans").val(means);
				$("#newoptessential").prop( "checked", essential == '1' );
				$("#newitemopt_loading").html('');
				$("#add_itemopt").prop( "disabled", false );
			}).fail(function(msg){
				$("#itemopt_ajax-response").html(msg);
				$("#newitemopt_loading").html('');
			});
			return false;
		},

		dosort : function( str ) {
			if( !str ) return;
			var id = $("#post_ID").val();
			var meta_id_str = str.replace(/itemopt-/g, "");
			var meta_ids = meta_id_str.split(',');
	
			var wc_nonce = $("#wc_nonce").val();
			var _wp_http_referer = $("input[name='_wp_http_referer']").val();

			if( 2 > meta_ids.length ) return;

			for(i=0; i<meta_ids.length; i++){
				$("#itemopt_loading-" + meta_ids[i]).html('<img src="' + uscesL10n.USCES_PLUGIN_URL + '/images/loading.gif" />');
			}
			var s = itemOpt.settings;
			s.data = "action=item_option_ajax&ID=" + id + "&sort=1&meta=" + encodeURIComponent(meta_id_str) + "&wc_nonce=" + encodeURIComponent(wc_nonce)+ "&_wp_http_referer=" + encodeURIComponent(_wp_http_referer);
			$.ajax( s ).done(function( data ){
				console.log(data);
				$("#itemopt_ajax-response").html("");
				$("tbody#item-opt-list").html( data.meta_row );
				for(i=0; i<meta_ids.length; i++){
					$("#itemopt_loading-" + meta_ids[i]).html('');
					$("#itemopt-" + meta_ids[i]).css({'background-color': '#FF4'});
					$("#itemopt-" + meta_ids[i]).animate({ 'background-color': '#FFFFEE' }, 2000 );
				}
			}).fail(function( msg ){
				$("#opt_ajax-response").html('<div class="error"><p>error sort</p></div>');
			});
			return false;
		}
	};

	itemSku = {
		settings: {
			url: uscesL10n.requestFile,
			type: 'POST',
			cache: false
		},

		post : function(action, arg) {
			if( action == 'updateitemsku' ) {
				itemSku.updateitemsku(arg);
			} else if( action == 'deleteitemsku' ) {
				var ele_sku_key = $( '#itemsku-' + arg + ' input[name="itemsku[' + arg + '][key]"]' );
				if ( ele_sku_key.length ) {
					if( ! confirm( uscesL10n.message[25].replace( '********', ele_sku_key.val() ) ) ) {
						return;
					}
				}
				itemSku.deleteitemsku(arg);
			} else if( action == 'additemsku' ) {
				itemSku.additemsku();
			} else if( action == 'keyselect' ) {
				itemSku.keyselect(arg);
			}
		},

		additemsku : function() {
			var id = $("#post_ID").val();
			var name = $("#newskuname").val();
			var cprice = $("#newskucprice").val();
			var price = $("#newskuprice").val();
			var zaikonum = $("#newskuzaikonum").val();
			var zaiko = $("#newskuzaikoselect").val();
			var skudisp = $("#newskudisp").val();
			var skuunit = $("#newskuunit").val();
			var skugptekiyo = $("#newskugptekiyo").val();
			var applicable_taxrate = '';
			if( undefined != $( "#newsku_applicable_taxrate" ) ) {
				applicable_taxrate = $( "#newsku_applicable_taxrate option:selected" ).val();
			}
			var mes = '';
			if( '' == name )
				mes += '<p>'+uscesL10n.message[4]+'</p>';
			if( '' == price )
				mes += '<p>'+uscesL10n.message[5]+'</p>';
//			if( ! checkCode( name ) )
//				mes += '<p>'+uscesL10n.message[6]+'</p>';
			if( ! checkMoney( cprice ) )
				mes += '<p>'+uscesL10n.message[7]+'</p>';
			if( ! checkMoney( price ) )
				mes += '<p>'+uscesL10n.message[8]+'</p>';
			if( undefined != $("#itemOrderAcceptable") && $("#itemOrderAcceptable").prop("checked") ) {
				if( ! checkNumMinus( zaikonum ) )
					mes += '<p>'+uscesL10n.message[9]+'</p>';
			} else {
				if( ! checkNum( zaikonum ) )
					mes += '<p>'+uscesL10n.message[9]+'</p>';
			}
			if( '' != mes ){
				$("#sku_ajax-response").html('<div class="error">' + mes + '</div>');
				return false;
			}

			var skuadvance = '';
			if( undefined != $("*[name*='newskuadvance']") ) {
				if( 1 == $("*[name*='newskuadvance']").length ) {
					skuadvance = '&newskuadvance='+encodeURIComponent($("*[name*='newskuadvance']").val());
				} else {
					$("*[name*='newskuadvance']").each(function() {
						skuadvance += '&newskuadvance['+$(this).attr("id")+']='+encodeURIComponent($(this).val());
					});
				}
			}

			$("#newitemsku_loading").html('<img src="' + uscesL10n.USCES_PLUGIN_URL + '/images/loading.gif" />');

			var s = itemSku.settings;
			s.data = "action=item_sku_ajax&ID=" + id + "&newskuname=" + encodeURIComponent(name) + "&newskucprice=" + cprice + "&newskuprice=" + price + "&newskuzaikonum=" + zaikonum + "&newskuzaikoselect=" + encodeURIComponent(zaiko) + "&newskudisp=" + encodeURIComponent(skudisp) + "&newskuunit=" + encodeURIComponent(skuunit) + "&newskugptekiyo=" + skugptekiyo + skuadvance;
			if( '' != applicable_taxrate ) {
				s.data += "&newskutaxrate=" + applicable_taxrate;
			}
			var hookargs = {
				postId: id,
				price,
				cprice,
				zaikonum,
				zaiko,
				name,
				skudisp,
				skuunit,
				skugptekiyo,
				applicable_taxrate,
				skuadvance
			}
			$.ajax( s ).done(function( data ){
				$("#newitemsku_loading").html('');
				$("#sku_ajax-response").html("");
				$("table#skulist-table").removeAttr("style");
				var meta_id = data.meta_id;
				hookargs.metaId = meta_id;
				hookargs.response = data;
				if( 0 > meta_id ){
					$("#sku_ajax-response").html('<div class="error"><p>'+uscesL10n.message[10]+'</p></div>');
				}else{
					wp.hooks.doAction('usces.itemSku.additemsku.on-success.before-dom-update', hookargs);
					$("tbody#item-sku-list").html( data.meta_row );
					wp.hooks.doAction('usces.itemSku.additemsku.on-success.after-dom-update', hookargs);
					$("#itemsku-" + meta_id).css({'background-color': '#FF4'});
					$("#itemsku-" + meta_id).animate({ 'background-color': '#FFFFEE' }, 2000 );
					$("#newskuname").val("");
					$("#newskucprice").val("");
					$("#newskuprice").val("");
					$("#newskuzaikonum").val("");
					$("#newskuzaikonum").val("");
					$("#newskuzaikoselect").val(0);
					$("#newskudisp").val("");
					$("#newskuunit").val("");
					$("#newskugptekiyo").val(0);
					if( 0 < $("*[name*='newskuadvance']").length ) {
						$("*[name*='newskuadvance']").each(function(index, element) {
							$(this).val("");
						});
					}
					if( 0 < $("select[name*='newskuadvance']").length ) {
						$("select[name*='newskuadvance']").each(function(index, element) {
							$(this).val("");
						});
					}
				}
				//$("#itemsku-" + meta_id).css({'background-color': '#FF4'});
				//$("#itemsku-" + meta_id).animate({ 'background-color': '#FFFFEE' }, 2000 );
			}).fail(function( msg ){

				$("#sku_ajax-response").html(msg);
				$("#newitemsku_loading").html('');
			});
			return false;
		},

		updateitemsku : function(meta_id) {
			var id = $("#post_ID").val();
			ks = document.getElementById('itemsku\['+meta_id+'\]\[key\]');
			cs = document.getElementById('itemsku\['+meta_id+'\]\[cprice\]');
			ps = document.getElementById('itemsku\['+meta_id+'\]\[price\]');
			ns = document.getElementById('itemsku\['+meta_id+'\]\[zaikonum\]');
			zs = document.getElementById('itemsku\['+meta_id+'\]\[zaiko\]');
			ds = document.getElementById('itemsku\['+meta_id+'\]\[skudisp\]');
			us = document.getElementById('itemsku\['+meta_id+'\]\[skuunit\]');
			gs = document.getElementById('itemsku\['+meta_id+'\]\[skugptekiyo\]');
			//ad = document.getElementById('itemsku\['+meta_id+'\]\[skuadvance\]');
			//ad = $("*[name*='itemsku\["+meta_id+"\]\[skuadvance\]']");
			ad = $("*[name*='itemsku\["+meta_id+"\]\[skuadvance\]']:not(:disabled)");
			so = document.getElementById('itemsku\['+meta_id+'\]\[sort\]');
			var name = $(ks).val();
			var cprice = $(cs).val();
			var price = $(ps).val();
			var zaikonum = $(ns).val();
			var zaiko = $(zs).val();
			var skudisp = $(ds).val();
			var skuunit = $(us).val();
			var skugptekiyo = $(gs).val();
			var sortnum = $(so).val();
			var applicable_taxrate = '';
			if( undefined != $( "select[name='itemsku\["+meta_id+"\]\[applicable_taxrate\]']" ) ) {
				applicable_taxrate = $( "select[name='itemsku\["+meta_id+"\]\[applicable_taxrate\]'] option:selected" ).val();
			}

			var mes = '';
			if( '' == name )
				mes += '<p>'+uscesL10n.message[4]+'</p>';
			if( '' == price )
				mes += '<p>'+uscesL10n.message[5]+'</p>';
			if( ! checkMoney( cprice ) )
				mes += '<p>'+uscesL10n.message[7]+'</p>';
			if( ! checkMoney( price ) )
				mes += '<p>'+uscesL10n.message[8]+'</p>';
			//if( ! checkNum( zaikonum ) )
			//	mes += '<p>'+uscesL10n.message[9]+'</p>';
			if( undefined != $("#itemOrderAcceptable") && $("#itemOrderAcceptable").prop("checked") ) {
				if( ! checkNumMinus( zaikonum ) )
					mes += '<p>'+uscesL10n.message[9]+'</p>';
			} else {
				if( ! checkNum( zaikonum ) )
					mes += '<p>'+uscesL10n.message[9]+'</p>';
			}
			if( '' != mes ){
				$("#sku_ajax-response").html('<div class="error">' + mes + '</div>');
				return false;
			}

			var skuadvance = '';
			if( undefined != $(ad) ) {
				if( 1 == $(ad).length ) {
					skuadvance = '&skuadvance='+encodeURIComponent($(ad).val());
				} else {
					$(ad).each(function() {
						skuadvance += '&skuadvance['+$(this).attr("id")+']='+encodeURIComponent($(this).val());
					});
				}
			}

			$("#itemsku_loading-" + meta_id).html('<img src="' + uscesL10n.USCES_PLUGIN_URL + '/images/loading.gif" />');
			var s = itemSku.settings;
			s.data = "action=item_sku_ajax&ID=" + id + "&update=1&skuprice=" + price + "&skucprice=" + cprice + "&skuzaikonum=" + zaikonum + "&skuzaiko=" + encodeURIComponent(zaiko) + "&skuname=" + encodeURIComponent(name) + "&skudisp=" + encodeURIComponent(skudisp) + "&skuunit=" + encodeURIComponent(skuunit) + "&skugptekiyo=" + skugptekiyo + "&sort=" + sortnum + "&skumetaid=" + meta_id + skuadvance;
			if( '' != applicable_taxrate ) {
				s.data += "&skutaxrate=" + applicable_taxrate;
			}
			var hookargs = {
				metaId: meta_id,
				postId: id,
				price,
				cprice,
				zaikonum,
				zaiko,
				name,
				skudisp,
				skuunit,
				skugptekiyo,
				sortnum,
				skuadvance
			}
			$.ajax( s ).done(function( data ){
				hookargs.response = data;
				$("#itemsku_loading-" + meta_id).html('');
				$("#sku_ajax-response").html("");
				$("table#skulist-table").removeAttr("style");
				var id = data.meta_id;
				if( data.meta_msg && '' != data.meta_msg ) {
					$("#sku_ajax-response").html('<div class="error"><p>'+data.meta_msg+'</p></div>');
				}  else if( 0 > id ){
					$("#sku_ajax-response").html('<div class="error"><p>'+uscesL10n.message[10]+'</p></div>');
				}else{
					wp.hooks.doAction('usces.itemSku.updateitemsku.on-success.before-dom-update', hookargs);
					$("tbody#item-sku-list").html( data.meta_row );
					wp.hooks.doAction('usces.itemSku.updateitemsku.on-success.after-dom-update', hookargs);
					$("#itemsku-" + meta_id).css({'background-color': '#FF4'});
					$("#itemsku-" + meta_id).animate({ 'background-color': '#FFFFEE' }, 2000 );
				}
			}).fail(function( msg ){
				$("#sku_ajax-response").html(msg);
				$("#itemsku_loading-" + meta_id).html('');
			});
			return false;
		},

		deleteitemsku : function(meta_id) {
			var data=[];
			$("#itemsku-" + meta_id).css({'background-color': '#F00'});
			$("#itemsku-" + meta_id).animate({ 'background-color': '#FFFFEE' }, 1000 );
			var id = $("#post_ID").val();
			var s = itemSku.settings;
			s.data = "action=item_sku_ajax&ID=" + id + "&delete=1&skumetaid=" + meta_id;
			$.ajax( s ).done(function( data ){
				if( data.meta_msg && '' != data.meta_msg ) {
					$("#sku_ajax-response").html('<div class="error"><p>'+data.meta_msg+'</p></div>');
				} else {
					$("#itemsku_loading-" + meta_id).html('');
					$("#sku_ajax-response").html("");
					var hookargs = {
						data,
						metaId: meta_id,
						postId: id,
					}
					wp.hooks.doAction('usces.itemSku.deleteitemsku.on-success.before-dom-update', hookargs);
					$("tbody#item-sku-list").html( data.meta_row );
					wp.hooks.doAction('usces.itemSku.deleteitemsku.on-success.after-dom-update', hookargs);
				}
			}).fail(function( msg ){
				$("#sku_ajax-response").html(msg);
				$("#itemsku_loading-" + meta_id).html('');
			});
			return false;
		},

		dosort : function( str ) {
			if( !str ) return;
			var id = $("#post_ID").val();

			var meta_str = '';
			var meta_ids = [];
			for(i=0; i<str.length; i++){

				if( undefined == str[i] ) continue;

				if( str[i].match(/itemsku-/) ){
					meta_str = str[i].replace(/[itemsku\-|,]/g, "")
					meta_ids.push(meta_str);
				}

			}
			var meta_id_str = meta_ids.join(',');

			if( 2 > meta_ids.length ) return;

			for(i=0; i<meta_ids.length; i++){
				$("#itemsku_loading-" + meta_ids[i]).html('<img src="' + uscesL10n.USCES_PLUGIN_URL + '/images/loading.gif" />');
			}
			var s = itemSku.settings;
			s.data = "action=item_sku_ajax&ID=" + id + "&sort=1&meta=" + encodeURIComponent(meta_id_str);
			$.ajax( s ).done(function( data ){
				$("#sku_ajax-response").html("");
				var hookargs = {
					data,
					metaIds: meta_ids,
					postId: id,
				}
				wp.hooks.doAction('usces.itemSku.dosort.on-success.before-dom-update', hookargs);
				$("tbody#item-sku-list").html( data.meta_row );
				wp.hooks.doAction('usces.itemSku.dosort.on-success.after-dom-update', hookargs);
				for(i=0; i<meta_ids.length; i++){
					//$("#itemsku_loading-" + meta_ids[i]).html('');
					$("#itemsku-" + meta_ids[i]).css({'background-color': '#FF4'});
					$("#itemsku-" + meta_ids[i]).animate({ 'background-color': '#FFFFEE' }, 2000 );
				}
			}).fail(function( msg ){
				$("#sku_ajax-response").html('<div class="error"><p>error sort</p></div>');
			});
			return false;
		}
	};

	payment = {
		settings: {
			url: uscesL10n.requestFile,
			type: 'POST',
			cache: false
		},

		post : function(action, arg) {
			if( action == 'update' ) {
				payment.update(arg);
			} else if( action == 'del' ) {
				payment.del(arg);
			} else if( action == 'add' ) {
				payment.add();
			}
		},

		add : function() {
			var name = $("#newname").val();
			var explanation = $("#newexplanation").val();
			var settlement = $("#newsettlement").val();
			var module = $("#newmodule").val();
			var wc_nonce = $("#wc_nonce").val();
			var _wp_http_referer = $("input[name='_wp_http_referer']").val();

			var mes = '';
			if( '' == name ){
				mes += '<p>'+uscesL10n.message[11]+'</p>';
			}
			if( '#NONE#' == settlement ) {
				mes += '<p>'+uscesL10n.message[12]+'</p>';
			}
			if( 'acting' == settlement ) {
				if( '' == module ) {
					mes += '<p>'+uscesL10n.message[13]+'</p>';
				}
			}
			if( '' != mes ){
				$("#payment_ajax-response").html('<div class="error">' + mes + '</div>');
				return false;
			}

			$("#newpayment_loading").html('<img src="' + uscesL10n.USCES_PLUGIN_URL + '/images/loading.gif" />');

			var s = payment.settings;
			s.data = "action=payment_ajax&newname=" + encodeURIComponent(name) + "&newexplanation=" + encodeURIComponent(explanation) + "&newsettlement=" + encodeURIComponent(settlement) + "&newmodule=" + encodeURIComponent(module) + "&wc_nonce=" + encodeURIComponent(wc_nonce) + "&_wp_http_referer=" + encodeURIComponent(_wp_http_referer);
			$.ajax( s ).done(function( data ){
				$("#newpayment_loading").html('');
				$("#payment_ajax-response").html('');
				$("table#payment-table").removeAttr("style");
				if( -1 == data.meta_id ){
					$("#payment_ajax-response").html('<div class="error"><p>'+uscesL10n.message[14]+'</p></div>');
				}else{
					$("tbody#payment-list").html( data.meta_row );
					$("#newname").val("");
					$("#newexplanation").val("");
					$("#newsettlement").val('acting');
					$("#newmodule").val("");
					$("#payment-" + data.meta_id).css({'background-color': '#FF4'});
					$("#payment-" + data.meta_id).animate({ 'background-color': '#FFFFEE' }, 2000 );
				}
			}).fail(function( msg ){
				$("#payment_ajax-response").html(msg);
				$("#newpayment_loading").html('');
			});
			return false;
		},

		update : function(id) {
			vn = document.getElementById('payment\[' + id + '\]\[name\]');
			ve = document.getElementById('payment\[' + id + '\]\[explanation\]');
			vs = document.getElementById('payment\[' + id + '\]\[settlement\]');
			vm = document.getElementById('payment\[' + id + '\]\[module\]');
			so = document.getElementById('payment\[' + id + '\]\[sort\]');
			var name = $(vn).val();
			var explanation = $(ve).val();
			var settlement = $(vs).val();
			var module = $(vm).val();
			var sortid = $(so).val();
			var use = $("input[name='payment[" + id + "][use]']:checked").val();
			var wc_nonce = $("#wc_nonce").val();
			var _wp_http_referer = $("input[name='_wp_http_referer']").val();
			var s = payment.settings;

			var mes = '';
			if( '' == name ){
				mes += '<p>'+uscesL10n.message[11]+'</p>';
			}
			if( '#NONE#' == settlement && 'activate' == use ) {
				mes += '<p>'+uscesL10n.message[12]+'</p>';
			}
			if( 'acting' == settlement ) {
				if( '' == module ) {
					mes += '<p>'+uscesL10n.message[13]+'</p>';
				}
			}
			if( '' != mes ){
				$("#payment_ajax-response").html('<div class="error">' + mes + '</div>');
				return false;
			}

			$("#payment_loading-" + id).html('<img src="' + uscesL10n.USCES_PLUGIN_URL + '/images/loading.gif" />');

			s.data = "action=payment_ajax&update=1&id=" + id + "&name=" + encodeURIComponent(name) + "&explanation=" + encodeURIComponent(explanation) + "&settlement=" + encodeURIComponent(settlement) + "&module=" + encodeURIComponent(module) + "&sort=" + sortid + "&use=" + use + "&wc_nonce=" + encodeURIComponent(wc_nonce) + "&_wp_http_referer=" + encodeURIComponent(_wp_http_referer);
			$.ajax( s ).done(function( data ){
				$("#payment_loading-" + id).html('');
				$("#payment_ajax-response").html("");
				if( -1 == data.meta_id ){
					$("#payment_ajax-response").html('<div class="error"><p>'+uscesL10n.message[14]+'</p></div>');
				}else{
					$("tbody#payment-list").html( data.meta_row );
					$("#payment-" + id).css({'background-color': '#FF4'});
					$("#payment-" + id).animate({ 'background-color': '#FFFFEE' }, 2000 );
				}
			}).fail(function( msg ){
				$("#payment_ajax-response").html(msg);
				$("#newpayment_loading").html('');
			});
			return false;
		},

		del : function(id) {
			$("#payment-" + id).css({'background-color': '#F00'});
			$("#payment-" + id).animate({ 'background-color': '#FFFFEE' }, 1000 );
			var wc_nonce = $("#wc_nonce").val();
			var _wp_http_referer = $("input[name='_wp_http_referer']").val();
			var s = payment.settings;
			s.data = "action=payment_ajax&delete=1&id=" + id + "&wc_nonce=" + encodeURIComponent(wc_nonce) + "&_wp_http_referer=" + encodeURIComponent(_wp_http_referer);
			$.ajax( s ).done(function( data ){
				$("tbody#payment-list").html( data.meta_row );
			}).fail(function( msg ){
				$("#payment_ajax-response").html(msg);
			});
			return false;
		},

		dosort : function( str ) {
			if( !str ) return;
			var meta_id_str = str.replace(/payment-/g, "");
			var meta_ids = meta_id_str.split(',');
			if( 2 > meta_ids.length ) return;

			for(i=0; i<meta_ids.length; i++){
				$("#payment_loading-" + meta_ids[i]).html('<img src="' + uscesL10n.USCES_PLUGIN_URL + '/images/loading.gif" />');
			}
			var wc_nonce = $("#wc_nonce").val();
			var _wp_http_referer = $("input[name='_wp_http_referer']").val();
			var s = payment.settings;
			s.data = "action=payment_ajax&sort=1&idstr=" + encodeURIComponent(meta_id_str) + "&wc_nonce=" + encodeURIComponent(wc_nonce) + "&_wp_http_referer=" + encodeURIComponent(_wp_http_referer);
			$.ajax( s ).done(function( data ){
				$("tbody#payment-list").html( data.meta_row );
				for(i=0; i<meta_ids.length; i++){
					$("#payment_loading-" + meta_ids[i]).html('');
					$("#payment-" + meta_ids[i]).css({'background-color': '#FF4'});
					$("#payment-" + meta_ids[i]).animate({ 'background-color': '#FFFFEE' }, 2000 );
				}
			}).fail(function(msg){
				$("#payment_ajax-response").html('<div class="error"><p>error sort</p></div>');
			});
			return false;
		}
	};

	orderItem = {
		settings: {
			url: uscesL10n.requestFile,
			type: 'POST',
			cache: false
		},

		add2cart : function(newid, newsku) {
			var ID = $("input[name='order_id']").val();
			var optob;
			var optvalue = '';
			var query = 'action=order_item2cart_ajax&order_id='+ID+'&post_id='+newid+'&sku='+newsku;

			var newoptob = $("input[name*='optNEWCode\[" + newid + "\]\[" + newsku + "\]']");
			var newoptvalue = '';
			var mes = '';
			for( var n = 0; n < newoptob.length; n++) {
				newoptvalue = $(":input[name='itemNEWOption\[" + newid + "\]\[" + newsku + "\]\[" + $(newoptob[n]).val() + "\]']").val();
				var newoptclass = $(":input[name='itemNEWOption\[" + newid + "\]\[" + newsku + "\]\[" + $(newoptob[n]).val() + "\]']").attr("class");
				var essential = $(":input[name='optNEWEssential\[" + newid + "\]\[" + newsku + "\]\[" + $(newoptob[n]).val() + "\]']").val();
				switch(newoptclass) {
				case 'iopt_select_multiple':
					var sel = 0;
					if( essential == 1 ) {
						$(":input[name='itemNEWOption\[" + newid + "\]\[" + newsku + "\]\[" + $(newoptob[n]).val() + "\]'] option:selected").each(function(idx, obj) {
							if( '#NONE#' != $(this).val()) {
								sel++;
							}
						});
						if( sel == 0 ) {
							mes += uscesL10n.message[15].replace( "%s", decodeURIComponent($(newoptob[n]).val()) )+"\n";
						}
					}
					$(":input[name='itemNEWOption\[" + newid + "\]\[" + newsku + "\]\[" + $(newoptob[n]).val() + "\]'] option:selected").each(function(idx, obj) {
						if( '#NONE#' != $(this).val()) {
							query += "&itemOption[" + $(newoptob[n]).val() + "][" + encodeURIComponent($(this).val()) + "]="+encodeURIComponent($(this).val());
						}
					});
					break;
				case 'iopt_select':
					if( essential == 1 && newoptvalue == '#NONE#' ) {
						mes += uscesL10n.message[15].replace( "%s", decodeURIComponent($(newoptob[n]).val()) )+"\n";
					} else {
						query += "&itemOption[" + $(newoptob[n]).val() + "]="+encodeURIComponent(newoptvalue);
					}
					break;
				case 'iopt_radio':
					var sel = 0;
					var ra = '';
					if( $(":input[name='itemNEWOption\[" + newid + "\]\[" + newsku + "\]\[" + $(newoptob[n]).val() + "\]']:checked").val() ) {
						sel++;
						ra = $(":input[name='itemNEWOption\[" + newid + "\]\[" + newsku + "\]\[" + $(newoptob[n]).val() + "\]']:checked").val();
					}
					if( essential == 1 && sel == 0 ) {
						mes += uscesL10n.message[15].replace( "%s", decodeURIComponent($(newoptob[n]).val()) )+"\n";
					}
					query += "&itemOption[" + $(newoptob[n]).val() + "]=" + ra;
					break;
				case 'iopt_checkbox':
					var sel = 0;
					if( essential == 1 ) {
						$(":input[name='itemNEWOption\[" + newid + "\]\[" + newsku + "\]\[" + $(newoptob[n]).val() + "\]']:checked").each(function(idx, obj) {
							if( '' != $(this).val()) {
								sel++;
							}
						});
						if( sel == 0 ) {
							mes += uscesL10n.message[15].replace( "%s", decodeURIComponent($(newoptob[n]).val()) )+"\n";
						}
					}
					$(":input[name='itemNEWOption\[" + newid + "\]\[" + newsku + "\]\[" + $(newoptob[n]).val() + "\]']:checked").each(function(idx, obj) {
						if( $(this).val()) {
							query += "&itemOption[" + $(newoptob[n]).val() + "][" + $(this).val() + "]=" + $(this).val();
						}
					});
					break;
				case 'iopt_text':
				case 'iopt_textarea':
					if( essential == 1 && newoptvalue == '' ) {
						mes += uscesL10n.message[16].replace( "%s", decodeURIComponent($(newoptob[n]).val()) )+"\n";
					} else {
						query += "&itemOption[" + $(newoptob[n]).val() + "]="+encodeURIComponent(newoptvalue);
					}
					break;
				}
			}
			if( mes != '' ) {
				alert(mes);
				return;
			}

			var s = orderItem.settings;
			s.data = query;
			$.ajax( s ).done(function( data ){
				$("#orderitemlist").html(data);
				orderfunc.sumPrice(null);
			}).fail(function( msg ){
				$("#order-response").html(msg);
			});
			return false;
		},

		add2cart_old : function(newid, newsku) {
			var ID = $("input[name='order_id']").val();
			var cnum = $("#orderitemlist").children().length;
			var priceob = $("input[name*='skuPrice']");
			var quantob = $("input[name*='quant']");
			var name = '';
			var strs = '';
			var post_ids = [];
			var skus = [];
			var optob;
			var optvalue = '';
			var query = 'action=order_item2cart_ajax&order_id='+ID;
			for( var i = 0; i < cnum; i++) {
				name = $(priceob[i]).attr("name");
				strs = name.split('[');
				post_ids[i] = strs[2].replace(/[\]]+$/g, '');
				skus[i] = strs[3].replace(/[\]]+$/g, '');

				query += "&skuPrice["+i+"]["+post_ids[i]+"]["+skus[i]+"]="+$("input[name='skuPrice\[" + i + "\]\[" + post_ids[i] + "\]\[" + skus[i] + "\]']").val();
				query += "&quant["+i+"]["+post_ids[i]+"]["+skus[i]+"]="+$("input[name='quant\[" + i + "\]\[" + post_ids[i] + "\]\[" + skus[i] + "\]']").val();

				optob = $("input[name*='optName\[" + i + "\]\[" + post_ids[i] + "\]\[" + skus[i] + "\]']");
				optvalue = '';
				for( var o = 0; o < optob.length; o++) {
					//optvalue = $("input[name='itemOption\[" + i + "\]\[" + post_ids[i] + "\]\[" + skus[i] + "\]\[" + $(optob[o]).val() + "\]']").val();
					//if( '#NONE#' != optvalue){
					//	query += "&itemOption["+i+"]["+post_ids[i]+"]["+skus[i]+"][" + $(optob[o]).val() + "]="+optvalue;
					//}
					var cnt = 0;
					$("input[name^='itemOption\[" + i + "\]\[" + post_ids[i] + "\]\[" + skus[i] + "\]\[" + $(optob[o]).val() + "\]\[']").each(function(idx, obj) {
						cnt++;
					});
					if(0 < cnt) {
						$("input[name^='itemOption\[" + i + "\]\[" + post_ids[i] + "\]\[" + skus[i] + "\]\[" + $(optob[o]).val() + "\]\[']").each(function(idx, obj) {
							query += "&itemOption["+i+"]["+post_ids[i]+"]["+skus[i]+"][" + $(optob[o]).val() + "][" + $(this).val() + "]="+$(this).val();
						});
					} else {
						optvalue = $("input[name='itemOption\["+i+"\]\["+post_ids[i]+"\]\["+skus[i]+"\]\["+$(optob[o]).val()+"\]']").val();
						query += "&itemOption["+i+"]["+post_ids[i]+"]["+skus[i]+"]["+$(optob[o]).val()+"]="+optvalue;
					}
				}
			}
			query += "&skuPrice["+cnum+"]["+newid+"]["+newsku+"]="+$("input[name='skuNEWPrice\[" + newid + "\]\[" + newsku + "\]']").val();
			query += "&quant["+cnum+"]["+newid+"]["+newsku+"]=1";
			var newoptob = $("input[name*='optNEWCode\[" + newid + "\]\[" + newsku + "\]']");
			var newoptvalue = '';
			var mes = '';
			for( var n = 0; n < newoptob.length; n++) {
				//newoptvalue = $("select[name='itemNEWOption\[" + newid + "\]\[" + newsku + "\]\[" + $(newoptob[n]).val() + "\]']").val();
				newoptvalue = $(":input[name='itemNEWOption\[" + newid + "\]\[" + newsku + "\]\[" + $(newoptob[n]).val() + "\]']").val();
				var newoptclass = $(":input[name='itemNEWOption\[" + newid + "\]\[" + newsku + "\]\[" + $(newoptob[n]).val() + "\]']").attr("class");
				var essential = $(":input[name='optNEWEssential\[" + newid + "\]\[" + newsku + "\]\[" + $(newoptob[n]).val() + "\]']").val();
				switch(newoptclass) {
				case 'iopt_select_multiple':
					var sel = 0;
					if( essential == 1 ) {
						$(":input[name='itemNEWOption\[" + newid + "\]\[" + newsku + "\]\[" + $(newoptob[n]).val() + "\]'] option:selected").each(function(idx, obj) {
							if( '#NONE#' != $(this).val()) {
								sel++;
							}
						});
					}
					if( sel == 0 ) {
						mes += uscesL10n.message[15].replace( "%s", decodeURIComponent($(newoptob[n]).val()) )+"\n";
					} else {
						$(":input[name='itemNEWOption\[" + newid + "\]\[" + newsku + "\]\[" + $(newoptob[n]).val() + "\]'] option:selected").each(function(idx, obj) {
							if( '#NONE#' != $(this).val()) {
								query += "&itemOption["+cnum+"]["+newid+"]["+newsku+"][" + $(newoptob[n]).val() + "][" + $(this).val() + "]="+$(this).val();
							}
						});
					}
					break;
				case 'iopt_select':
					if( essential == 1 && newoptvalue == '#NONE#' ) {
						mes += uscesL10n.message[15].replace( "%s", decodeURIComponent($(newoptob[n]).val()) )+"\n";
					} else {
						query += "&itemOption["+cnum+"]["+newid+"]["+newsku+"][" + $(newoptob[n]).val() + "]="+newoptvalue;
					}
					break;
				case 'iopt_text':
				case 'iopt_textarea':
					if( essential == 1 && newoptvalue == '' ) {
						mes += uscesL10n.message[16].replace( "%s", decodeURIComponent($(newoptob[n]).val()) )+"\n";
					} else {
						query += "&itemOption["+cnum+"]["+newid+"]["+newsku+"][" + $(newoptob[n]).val() + "]="+newoptvalue;
					}
					break;
				}
			}
			if( mes != '' ) {
				alert(mes);
				return;
			}

			var s = orderItem.settings;
			s.data = query;
			$.ajax( s ).done(function( data ){
				//if(data == 'nodata'){return;}
				if( 0 > parseInt(data.meta_row) ) return;
				var pict = "<img src='" + $("#newitemform img").attr("src") + "' width='" + $("#newitemform img").attr("width") + "' height='" + $("#newitemform img").attr("height") + "' alt='' />";
				//var itemName = $("input[name='itemNEWName\["+newid+"\]\["+newsku+"\]']").val() + ' ' + $("input[name='itemNEWCode\["+newid+"\]\["+newsku+"\]']").val() + ' ' + $("input[name='skuNEWName\["+newid+"\]\["+newsku+"\]']").val();
				var itemName = data.meta_id;
				var zaiko = $("input[name='zaiNEWko\["+newid+"\]\["+newsku+"\]']").val();
				//var price = "<input name='skuPrice[" + cnum + "][" + newid + "][" + newsku + "]' class='text price' type='text' value='" + $("input[name='skuNEWPrice\["+newid+"\]\["+newsku+"\]']").val() + "' onchange='orderfunc.sumPrice()' />";
				//var quant = "<input name='quant[" + cnum + "][" + newid + "][" + newsku + "]' class='text quantity' type='text' value='1' onchange='orderfunc.sumPrice()' />";
				//var price = "<input name='skuPrice[" + cnum + "][" + newid + "][" + newsku + "]' class='text price' type='text' value='" + $("input[name='skuNEWPrice\["+newid+"\]\["+newsku+"\]']").val() + "' />";
				var price = "<input name='skuPrice[" + cnum + "][" + newid + "][" + newsku + "]' class='text price' type='text' value='" + data.meta_row + "' />";
				var quant = "<input name='quant[" + cnum + "][" + newid + "][" + newsku + "]' class='text quantity' type='text' value='1' />";
				var delButton = "<input name='delButton[" + cnum + "][" + newid + "][" + newsku + "]' class='delCartButton' type='submit' value='"+uscesL10n.message[18]+"' />\n<input name='advance[" + cnum + "][" + newid + "][" + newsku + "]' type='hidden' value='' />\n";

				var sucoptob = $("input[name*='optNEWCode\[" + newid + "\]\[" + newsku + "\]']");
				var skuOptValue = '';
				var skuoptval = '';
				var hiddenopt = '';
				var hiddenoptname = '';
				for( var i = 0; i < sucoptob.length; i++) {
					skuoptcode = $(sucoptob[i]).val();
					skuoptname = decodeURIComponent($(sucoptob[i]).val());
					hiddenoptname += "<input name='optName[" + newid + "][" + newsku + "][" + skuoptcode + "]' type='hidden' value='"+skuoptcode+"' />\n";
					//skuoptval = $("select[name='itemNEWOption\[" + newid + "\]\[" + newsku + "\]\[" + skuoptcode + "\]']").val();
					skuoptval = $(":input[name='itemNEWOption\[" + newid + "\]\[" + newsku + "\]\[" + skuoptcode + "\]']").val();
					//if( '#NONE#' != skuoptval){
					//	skuOptValue += skuoptval + ' ';
					//	hiddenopt += "<input name='itemOption[" + cnum + "][" + newid + "][" + newsku + "][" + skuoptcode + "]' type='hidden' value='"+skuoptval+"' />";
					//	hiddenoptname += "<input name='optName[" + cnum + "][" + newid + "][" + newsku + "][" + skuoptcode + "]' type='hidden' value='"+skuoptcode+"' />";
					//}
					skuOptValue += skuoptname + ' : ';
					var sucoptclass = $(":input[name='itemNEWOption\[" + newid + "\]\[" + newsku + "\]\[" + skuoptcode + "\]']").attr("class");
					switch(sucoptclass) {
					case 'iopt_select_multiple':
						var c = '';
						$(":input[name='itemNEWOption\[" + newid + "\]\[" + newsku + "\]\[" + skuoptcode + "\]'] option:selected").each(function(idx, obj) {
							if( '#NONE#' != $(this).val() ) {
								skuOptValue += c + $(this).val();
								c = ', ';
								hiddenopt += "<input name='itemOption[" + cnum + "][" + newid + "][" + newsku + "][" + skuoptcode + "][" + encodeURIComponent($(this).val()) + "]' type='hidden' value='"+encodeURIComponent($(this).val())+"' />\n";
							}
						});
						break;
					case 'iopt_select':
						if( '#NONE#' != skuoptval ) {
							skuOptValue += skuoptval;
							hiddenopt += "<input name='itemOption[" + cnum + "][" + newid + "][" + newsku + "][" + skuoptcode + "]' type='hidden' value='"+encodeURIComponent(skuoptval)+"' />\n";
						}
						break;
					case 'iopt_text':
					case 'iopt_textarea':
						skuOptValue += skuoptval;
						hiddenopt += "<input name='itemOption[" + cnum + "][" + newid + "][" + newsku + "][" + skuoptcode + "]' type='hidden' value='"+encodeURIComponent(skuoptval)+"' />\n";
						break;
					}
					skuOptValue += '<br />';
				}
				var htm = "<tr>\n";
				htm += "<td>"+(cnum+1)+"</td>\n";
				htm += "<td>"+pict+"</td>\n";
				htm += "<td class='aleft'>"+itemName+"<br />"+skuOptValue+"</td>\n";
				htm += "<td>"+price+"</td>\n";
				htm += "<td>"+quant+"</td>\n";
				htm += "<td><p id='sub_total["+cnum+"]' class='aright'>&nbsp;</p></td>\n";
				htm += "<td>"+zaiko+"</td>\n";
				htm += "<td>"+delButton+hiddenoptname+hiddenopt+"</td>\n";
				htm += "</tr>\n";

				$("#orderitemlist").append( htm );
				orderfunc.sumPrice(null);

				$("input[name='skuPrice["+cnum+"]["+newid+"]["+newsku+"]']").bind("change", {index:cnum, post_id:newid, sku:newsku}, function(e){ orderfunc.sumPrice($(this)); });
				$("input[name='quant["+cnum+"]["+newid+"]["+newsku+"]']").bind("change", {index:cnum, post_id:newid, sku:newsku}, function(e){ orderfunc.sumPrice($(this)); });
				$("input[name='delButton["+cnum+"]["+newid+"]["+newsku+"]']").bind("click", {index:cnum, post_id:newid, sku:newsku}, function(e){ return delConfirm($(this)); });
				$("input[name*='skuPrice[" + cnum + "][" + newid + "][" + newsku + "]']").bind("change", function(){ orderfunc.sumPrice($(this)); });
				$("input[name*='quant[" + cnum + "][" + newid + "][" + newsku + "]']").bind("change", function(){ orderfunc.sumPrice($(this)); });
				$("input[name*='delButton[" + cnum + "][" + newid + "][" + newsku + "]']").bind("click", function(){ orderfunc.sumPrice(null); });
			}).fail(function( msg ){
				$("#order-response").html(msg);
			});
			return false;
		},

		getSelitem : function( cat_id ) {
			if(cat_id == '-1'){
				$("#newitemcode").html('');
				return false;
			}
			$("#loading").html('<img src="' + uscesL10n.USCES_PLUGIN_URL + '/images/loading.gif" />');
			var s = orderItem.settings;
			s.data = "action=order_item_ajax&mode=get_item_select_option&cat_id=" + cat_id;
			$.ajax( s ).done(function( data ){
				$("#loading").html('');
				$("#newitemcode").html( data );
			}).fail(function( msg ){
				$("#order-response").html(msg);
			});
			return false;
		},

		getitem : function(itemcode) {
			if(itemcode == '-1'){
				$("#newitemform").html('');
				return false;
			}
			$("#loading").html('<img src="' + uscesL10n.USCES_PLUGIN_URL + '/images/loading.gif" />');
			var s = orderItem.settings;
			s.data = "action=order_item_ajax&mode=get_order_item&itemcode=" + itemcode;
			$.ajax( s ).done(function( data ){
				$("#loading").html('');
				$("#newitemform").html( data );
			}).fail(function( msg ){
				$("#order-response").html(msg);
			});
			return false;
		},

		getmailmessage : function( flag ) {
			$("#sendmailmessage").val( uscesL10n.now_loading );
			$("#usces_email_bnt_send").prop("disabled", true);
			var order_id = $("input[name='order_id']").val();
			var s = orderItem.settings;
			s.data = "action=order_item_ajax&mode=" + flag + "&order_id=" + order_id;
			$.ajax( s ).done(function( data ){
					$("#sendmailmessage").val( data );
					$("#usces_email_bnt_send").removeAttr('disabled');
			}).fail(function( msg ){
				$("#order-response").html(msg);
				$("#usces_email_bnt_send").removeAttr('disabled');
			});
			return false;
		},
		getMailMessageRichEditor : function(usceAdminUrl, mode ) {
			$("#loading_iframe").text(uscesL10n.now_loading);
			$("#usces_email_bnt_preview").prop("disabled", true);
			$("#usces_email_bnt_send").prop("disabled", true);
			$("#loading_iframe").show();
			var order_id = $("input[name='order_id']").val();
			usceAdminUrl += '?page=usces_orderlist&order_action=load_rich_editor&orderid='+order_id+'&mode='+mode+'&noheader=true';
			$('#iframeLoadEditor').attr('src', usceAdminUrl);  
			$('#iframeLoadEditor').load(function(){
				$("#loading_iframe").hide();
				$(this).show();
				$("#usces_email_bnt_preview").removeAttr('disabled');
				$("#usces_email_bnt_send").removeAttr('disabled');
			});
		}

	};

	uscesInformation = {
		settings: {
			url: 'http://www.welcart.com/util/welcart_information.php',
			type: 'POST',
			cache: false
		},

		getinfo : function() {
			var s = uscesInformation.settings;
			s.data = "v=" + encodeURIComponent(uscesL10n.version);
			s.data += "&wcid=" + encodeURIComponent(uscesL10n.wcid);
			s.data += "&wcurl=" + encodeURIComponent(uscesL10n.USCES_PLUGIN_URL);
			s.data += "&locale=" + encodeURIComponent(uscesL10n.locale);
			s.data += "&theme=" + encodeURIComponent(uscesL10n.theme);
			s.data += "&wcex=";
			var de = '';
			for( var i = 0; i < uscesL10n.wcex.length; i++) {
				s.data += de + encodeURIComponent(uscesL10n.wcex[i]);
				de =',';
			}
			$.ajax( s ).done(function( data ){
				$("#wc_information").html( data );
			}).fail(function( msg ){
				$("#wc_information").html( 'error : ' +  msg );
			});
			return false;
		},

		getinfo2 : function() {
			var s = uscesInformation.settings;
			s.url = uscesL10n.requestFile;
			s.data = 'action=getinfo_ajax';
			$.ajax( s ).done(function( data ){
				$("#wc_information").html( data );
			}).fail(function( msg ){
				$("#wc_information").html( 'error : ' +  msg );
			});
			return false;
		}

	};

	uscesItem = {

		newdraft : function(itemName) {
			if(jQuery("#title").val().length == 0 || jQuery("#title").val() == '') {
				$("#title").val(itemName);
			}
			//autosave();

		},

		cahngepict : function(code) {
			$("div#item-select-pict").html(code);
		},

		trim : function(target){
			target = target.replace(/(^\s+)|(\s+$)|(^\n+)|(\n+$)/g, "");
			return target;
		}

	};

	welPageNav = {
		checkShowNextprevPage : function( key_current_page_ids, sub_uri_link, current_id ) {
			var current_id = parseInt(current_id);
			if (0 > current_id) {
				return false;
			}
			try {
				var current_page_ids = window.localStorage.getItem(key_current_page_ids);
			} catch (e) {
				var current_page_ids = '';
			}
			if ( current_page_ids ) {
				var arr_current_page_ids = current_page_ids.split(",");
				if (0 > arr_current_page_ids.length) {
					return false;
				}
				var index_prev = -1;
				var index_next = -1;
				for (var i = 0; i < arr_current_page_ids.length; ++i) {
					if (arr_current_page_ids[i] == current_id) {
						index_prev = i - 1;
						index_next = i + 1;
						break;
					}
				}
				if ( arr_current_page_ids[index_prev] ) {
					var prev_link = sub_uri_link + arr_current_page_ids[index_prev];
					jQuery(".edit_pagenav .prev-page").attr('href', prev_link);
					jQuery(".edit_pagenav .prev-page").show();
				}
				if ( arr_current_page_ids[index_next] ) {
					var next_link = sub_uri_link + arr_current_page_ids[index_next];
					jQuery(".edit_pagenav .next-page").attr('href', next_link);
					jQuery(".edit_pagenav .next-page").show();
				}
			}
		}
	};

	$( document ).on( "change", "#newitemcategory", function() {
		orderItem.getSelitem( $(this).val() );
	});

	$( document ).on( "change", "#newitemcode", function() {
		orderItem.getitem( $(this).val() );
	});

	$("#getitembutton").click( function(){
		orderItem.getitem( encodeURIComponent($("#newitemcodein").val()) );
	});

	adminOperation = {
		setActionStatus : function( status, message ) {
			if( $("#usces_admin_status") == undefined || status == "" || message == "" ) return;

			var notice_class = "";
			if( status == "success" ) {
				notice_class = "updated";
			} else if( status == "caution" ){
				notice_class = "update-nag";
			} else if( status == "error" ) {
				notice_class = "error";
			}
			if( "" != notice_class ) {
				$("#usces_admin_status").html( "" );
				$("#usces_admin_status").html( '<div id="wc2-action-status" class="'+notice_class+' notice is-dismissible"><p><strong>'+message+'</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">'+uscesL10n.message[19]+'</span></button></div>' );
			}
		},

		removeActionStatus : function() {
			$("#usces_admin_status").html( "" );
			$("#usces_action_status").remove();
		}
	};

	$(document).on("click", ".notice-dismiss", function() {
		adminOperation.removeActionStatus();
	});

	$( document ).on( "click", ".explanation-label", function() {
		var idname = $( this ).attr( "id" );
		var explanation = "#"+idname.substr( 6 );
		if( $( explanation ).css( "display" ) == "table-row" ) {
			$( explanation ).css( "display", "none" );
		} else {
			$( explanation ).css( "display", "table-row" );
		}
	});
    $(document).on('submit',"form#datalistup_form",function () {
    	var wp_usc_member_cookie_path = (typeof $.cookie('wp_usces_member_path') != 'undefined') ? $.cookie('wp_usces_member_path') : '/wp-admin/';
    	var wp_usc_order_cookie_path = (typeof $.cookie('wp_usces_order_path') != 'undefined') ? $.cookie('wp_usces_order_path') : '/wp-admin/';

    	$.removeCookie("wp_usces_member", {path: wp_usc_member_cookie_path, domain: window.location.hostname});
    	$.removeCookie("wp_usces_order", {path: wp_usc_order_cookie_path, domain: window.location.hostname});
        return true;
    });
	$(document).on('click','#btn_download_env_info', function () {
        $.ajax({
            url: ajaxurl,
            type: "POST",
            data: {
                action: 'usces_download_system_information',
            }
        })
		.done(function (response) {
			response = jQuery.parseJSON(response);
            var element = document.createElement('a');
            element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(response.data));
            element.setAttribute('download', response.filename);

            element.style.display = 'none';
            document.body.appendChild(element);

            element.click();

            document.body.removeChild(element);
		})
		.fail(function (error) {
			alert(error.status + " " + error.statusText);
		})
    })
	$(document).on('submit', '#google_recaptcha_form', function (e) {
		var status = true;
		var gg_recaptcha_status = $(this).find('input[name="status"]:checked').val();
		if(gg_recaptcha_status == 1){
			var site_key = $('#site_key').val();
			var secret_key = $('#secret_key').val();
			if (!site_key.length) {
				$('#site_key').css('background-color', '#ffffaa');
				$('#error_google_recaptcha_site_key').css('display','block');
				status = false;
			}
			else {
				$('#site_key').css('background-color', '#fff');
				$('#error_google_recaptcha_site_key').css('display','none');
			}
			if (!secret_key.length) {
				$('#secret_key').css('background-color', '#ffffaa');
				$('#error_google_recaptcha_secret_key').css('display','block');
				status = false;
			}
			else{
				$('#secret_key').css('background-color', '#fff');
				$('#error_google_recaptcha_secret_key').css('display','none');
			}
		}
		return status;
	});
})(jQuery);

function usces_check_num(obj) {
	if(!checkNum(obj.val())) {
		alert(uscesL10n.message[17]);
		obj.focus();
		return false;
	}
	return true;
}
function usces_check_money(obj) {
	if(!checkMoney(obj.val())) {
		alert(uscesL10n.message[17]);
		obj.focus();
		return false;
	}
	return true;
}
function checkAlp(argValue) {
	if(argValue && argValue.match(/[^a-z|^A-Z]/g)) {
		return false;
	}
	return true;
}
function checkCode(argValue) {
	if(argValue && argValue.match(/[^0-9|^a-z|^A-Z|^\-|^_]/g)) {
		return false;
	}
	return true;
}
function checkNum(argValue) {
	if(argValue && argValue.match(/[^0-9]/g)) {
		return false;
	}
	return true;
}
function checkNumMinus(argValue) {
	if( argValue && argValue.match(/[^0-9|^\-]/g) ) {
		return false;
	}
	if( argValue.match(/[^\-]/g) ) {
	} else {
		return false;
	}
	var count = 0;
	for( i = 0; i < argValue.length; i++ ) {
		if( argValue.charAt(i) == "-" ) {
			count++;
		}
	}
	if( 2 <= count || (count == 1 && argValue.charAt(0) != "-") ) {
		return false;
	}
	return true;
}
function checkMoney(argValue) {
	if(argValue && argValue.match(/[^0-9|^\.]/g)) {
		return false;
	}
	return true;
}
function checkPrice(argValue) {
	if(argValue && argValue.match(/[^0-9|^\-|^\,|^\.]/g)) {
		return false;
	}
	return true;
}

function toggleVisibility(id) {
	var e = document.getElementById(id);
	if( e.style.display == 'block' ) {
		e.style.display = 'none';
	} else {
		e.style.display = 'block';
	}
}

function esc_js( str ) {
	return str.replace(/"/g,'&quot;').replace(/'/g,'&#x27;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}
