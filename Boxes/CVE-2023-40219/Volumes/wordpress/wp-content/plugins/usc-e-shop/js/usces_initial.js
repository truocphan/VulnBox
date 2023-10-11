jQuery(function($) {

	codSend = {
		settings: {
			url: uscesL10n.requestFile,
			type: 'POST',
			cache: false
		},

		send : function() {
			$("#cod-response").html('<img src="' + uscesL10n.USCES_PLUGIN_URL + '/images/loading-publish.gif" />');
			var query = 'action=setup_cod_ajax';
			var cod_type = $("input[name='cod_type']:checked").val();

			if( 'fix' == cod_type ){
				var cod_fee = $("input[name='cod_fee']").val();
				var cod_limit_amount = $("#cod_limit_amount_fix").val();
				query += '&cod_type=' + cod_type + '&cod_fee=' + cod_fee + '&cod_limit_amount=' + cod_limit_amount;
			}else{
				var cod_first_amount = $("input[name='cod_first_amount']").val();
				var cod_limit_amount = $("#cod_limit_amount_change").val();
				var cod_first_fee = $("input[name='cod_first_fee']").val();
				var cod_end_fee = $("input[name='cod_end_fee']").val();
				query += '&cod_type=' + cod_type + '&cod_first_amount=' + cod_first_amount + '&cod_limit_amount=' + cod_limit_amount + '&cod_first_fee=' + cod_first_fee + '&cod_end_fee=' + cod_end_fee;
				var amounts = $("input[name^='cod_amounts']");
				for(var i=0; i<amounts.length; i++){
					query += '&cod_amounts[]=' + $("input[name='cod_amounts\[" + i + "\]']").val() + '&cod_fees[]=' + $("input[name='cod_fees\[" + i + "\]']").val();
				}
			}

			var s = codSend.settings;
			s.data = query;
			$.ajax( s ).done(function( data ){
				var str = 'success' == data ? usces_ini.cod_updated : data;
				$("#cod-response").html( str );
				if( 'fix' == $("input[name='cod_type']:checked").val() ){
					$('#cod_type_field').html(usces_ini.cod_type_fix);
				}else if( 'change' == $("input[name='cod_type']:checked").val() ){
					$('#cod_type_field').html(usces_ini.cod_type_change);
				}
			}).fail(function( msg ){
				$("#cod-response").html(usces_ini.cod_failure);
			});
			return false;
		}
	};

	$("#cod_dialog").dialog({
		autoOpen: false,
		height: 500,
		width: 450,
		modal: true,
		buttons: [
			{
				text: usces_ini.cod_label_close,
				click: function() {
					$(this).dialog('close');
				}
			},
			{
				text: usces_ini.cod_label_update,
				click: function() {
					codSend.send();
				}
			}
		],
		close: function() {
			if( 'fix' == $("input[name='cod_type']:checked").val() ){
				$('#cod_type_field').html(usces_ini.cod_type_fix);
			}else if( 'change' == $("input[name='cod_type']:checked").val() ){
				$('#cod_type_field').html(usces_ini.cod_type_change);
			}
		}
	});

	$('#detailed_setting')
		.click(function() {
			$('#cod_dialog').dialog('open');
	});

	$('#cod_type_fix')
		.click(function() {
			$('#cod_fix_table').css("display","");
			$('#cod_change_table').css("display","none");
	});

	$('#cod_type_change')
		.click(function() {
			$('#cod_fix_table').css("display","none");
			$('#cod_change_table').css("display","");
	});

	$("input[name='cod_first_amount']")
		.change(function() {
			var trs = $("input[name^='cod_amounts']");
			var first_amount = $("input[name='cod_first_amount']");
			if( 0 == trs.length && $(first_amount).val() != '' ){
				$("#end_amount").html(parseInt($(first_amount).val())+1);
			}else if( 0 < trs.length && $(first_amount).val() != '' ){
				$('#amount_0').html(parseInt($(first_amount).val())+1);
			}
	});

	$("#cod_limit_amount_change")
		.change(function() {
		if( 'change' == $("input[name='cod_type']:checked").val() ){
			var pre = parseInt($("#end_amount").html());
			var limit = parseInt($("#cod_limit_amount_change").val());
			if( pre >= limit ){
				alert(usces_ini.cod_limit + pre + ':' + limit);
			}
		}
	});

	$("input[name^='cod_amounts']")
		.change(function() {
			var trs = $("input[name^='cod_amounts']");
			var cnt = $(trs).length;
			var id = $(trs).index(this);
			if( id >= cnt-1 ){
				$(end_amount).html( parseInt($(trs).eq(id).val()) + 1 );
			}else if( id < cnt-1 ){
				$('#amount_'+(id+1)).html( parseInt($(trs).eq(id).val()) + 1 );
			}
	});

	$('#add_row')
		.click(function() {
			var trs = $("input[name^='cod_amounts']");
			$(trs).unbind("change");
			var first_amount = $("input[name='cod_first_amount']");
			var first_fee = $("input[name='cod_first_fee']");
			var end_amount = $("#end_amount");
			var enf_fee = $("input[name='cod_enf_fee']");
			//alert(parseInt(first_amount)+':'+first_fee+':'+end_amount+':'+enf_fee+':'+trs.length);
			if( 0 == trs.length){
				prep = ( $(first_amount).val() == '' ) ? '' : parseInt( $(first_amount).val() )+1;
			}else if( 0 < trs.length){
				prep = ( $(trs).eq(trs.length-1).val() == '' ) ? '' : parseInt( $(trs).eq(trs.length-1).val() )+1;
			}
			html = '<tr id="tr_'+trs.length+'"><td class="cod_f"><span id="amount_'+trs.length+'">' + prep + '</span></td><td class="cod_m">'+uscesL10n.message[20]+'</td><td class="cod_e"><input name="cod_amounts['+trs.length+']" type="text" class="short_str num" /></td><td class="cod_cod"><input name="cod_fees['+trs.length+']" type="text" class="short_str num" /></td></tr>';
			$('#cod_change_field').append(html);
			trs = $("input[name^='cod_amounts']");
			$(trs).bind("change", function(){
				var cnt = $(trs).length;
				var id = $(trs).index(this);
				if( id >= cnt-1 ){
					$(end_amount).html( parseInt($(trs).eq(id).val()) + 1 );
				}else if( id < cnt-1 ){
					$('#amount_'+(id+1)).html( parseInt($(trs).eq(id).val()) + 1 );
				}
				return false;
			});
	});

	$('#del_row')
		.click(function() {
			var trs = $("input[name^='cod_amounts']");
			$(trs).unbind("change");
			var first_amount = $("input[name='cod_first_amount']");
			var end_amount = $("#end_amount");
			//alert(parseInt(first_amount)+':'+first_fee+':'+end_amount+':'+enf_fee+':'+trs.length);
			var del_id = trs.length - 1;
			//alert(trs.length);
			if( 0 < trs.length){
				$('#tr_'+del_id).remove();
			}
			trs = $("input[name^='cod_amounts']");
			if( 0 == trs.length && $(first_amount).val() != '' ){
				$(end_amount).html( parseInt($(first_amount).val())+1 );
			}else if( 0 < trs.length && $(trs).eq(trs.length-1).val() != '' ){
				$(end_amount).html( parseInt($(trs).eq(trs.length-1).val()) + 1 );
			}
			$(trs).bind("change", function(){
				var cnt = $(trs).length;
				var id = $(trs).index(this);
				
				if( id >= cnt-1 && $(trs).eq(id).val() != '' ){
					$(end_amount).html( parseInt($(trs).eq(id).val()) + 1 );
				}else if( id < cnt-1 && $(trs).eq(id).val() != '' ){
					$('#amount_'+(id+1)).html( parseInt($(trs).eq(id).val()) + 1 );
				}
			});
	});

	if( 'fix' == usces_ini.cod_type ){
		$('#cod_type_field').html(usces_ini.cod_type_fix);
		$('#cod_fix_table').css("display","");
		$('#cod_change_table').css("display","none");
	}else{
		$('#cod_type_field').html(usces_ini.cod_type_change);
		$('#cod_fix_table').css("display","none");
		$('#cod_change_table').css("display","");
	}
});
