// JavaScript
jQuery(function($) {

	var wc_check = 'same';
	setInterval(function(){

		if( 'same' != wc_check ){
			if( 'different' == wc_check ){

				alert( uscesL10n.check_mes );

			}else if( 'timeover' == wc_check ){

				alert( uscesL10n.check_mes );

			}else if( 'entrydiff' == wc_check ){

				alert( uscesL10n.check_mes );

			}
			location.href = uscesL10n.cart_url;
		}
		wc2confirm.check();

	}, 20000);


	wc2confirm = {
		settings: {
			url: uscesL10n.ajaxurl+'?uscesid='+uscesL10n.uscesid,
			type: 'POST',
			cache: false,
			data: {}
		},
		
		check : function() {
			var s = wc2confirm.settings;
			s.data = { 
				'action' : 'welcart_confirm_check',
				'uscesid' : uscesL10n.uscesid,
				'wc_condition' : uscesL10n.condition,
				'wc_nonce' : uscesL10n.wc_nonce
			};
			$.ajax( s ).done(function( data ){
				//$(".header_explanation").append(data);
				data = data.replace(/(^\s+)|(\s+$)|(^\r\n)|(\r\n$)|(^\n+)|(\n+$)/g, "");
				wc_check = data;
			}).fail(function( msg ){
				//$(".header_explanation").append(msg);
			});
			return false;
		}
	};


});

