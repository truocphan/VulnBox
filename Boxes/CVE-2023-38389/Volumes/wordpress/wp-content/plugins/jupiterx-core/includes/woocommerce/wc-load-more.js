jQuery(function($){
	$('.jupiterx-wc-load-more').click(function(){

		var button = $(this),
		    data = {
			'action': jupiterx_wc_loadmore_params.action,
			'query': jupiterx_wc_loadmore_params.posts,
			'page' : jupiterx_wc_loadmore_params.current_page,
			'security' : jupiterx_wc_loadmore_params.security,
			'orderby': $( '.orderby' ).val(),
		};

		$.ajax({
			url : jupiterx_wc_loadmore_params.ajaxurl,
			data : data,
			type : 'POST',
			beforeSend : function ( xhr ) {
				button.text(jupiterx_wc_loadmore_params.i18n.btn_text_loading);
			},
			success : function( response ){
        var products = '';
        var resultCount = '';

        if (!response || !response.data) {
          return;
        }

        if (response.data.products) {
          products = response.data.products;
          products = products.join('')
        }

        if (response.data.result_count) {
          resultCount = response.data.result_count
        }

		if (products) {
			button.text( jupiterx_wc_loadmore_params.i18n.btn_text ).parent().prev().append(products);
			jupiterx_wc_loadmore_params.current_page++;

			if ( jupiterx_wc_loadmore_params.current_page == jupiterx_wc_loadmore_params.max_page ) {
				button.remove(); // if last page, remove the button
          	}
		} else {
				button.remove();
        }

        if (resultCount) {
          $('.woocommerce-result-count').html(resultCount);
        }
			}
		});
	});
});
