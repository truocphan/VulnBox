jQuery(function($){
	// Fix quantity input styling after load more on quick view
	$('body').on('click','.jupiterx-product-has-quick-view',function () {
		setTimeout( addInputAndEvents );

		function addInputAndEvents() {
			// Add quantity input
			id = $('.featherlight-content .jupiterx-product-quick-view-modal').attr('id');

			if ( $('.featherlight-content #' + id + ' .custom-qty-input').length == 0 ) {
				$('.featherlight-content #' + id + ' .input-text.qty').hide();
				$('.featherlight-content #' + id + ' .input-text.qty').after('<div class=\"input-group input-text qty text custom-qty-input \"><div class=\"input-group-prepend\"><button style=\"min-width: 0; box-shadow: none;\" class=\"btn btn-decrement btn-sm btn-outline-secondary\" type=\"button\" tabindex=\"-1\"><strong>-</strong></button></div><input type=\"text\" value="" style=\"text-align: center\" class=\"form-control input-text qty text\" placeholder=\"\" tabindex=\"-1\"><div class=\"input-group-append\"><button style=\"min-width: 0; box-shadow: none;\" class=\"btn btn-increment btn-sm btn-outline-secondary\" type=\"button\" tabindex=\"-1\"><strong>+</strong></button></div></div>');
				$('.featherlight-content #' + id + ' .custom-qty-input .input-text.qty').val( $('.featherlight-content #' + id +' .input-text.qty').val() ) ;
			}

			// Change quantity value on input change
			$('.featherlight-content .jupiterx-product-quick-view-modal').on('keyup paste change', '.custom-qty-input .qty', function() {
				$('.featherlight-content #' + id + ' .input-text.qty').val( $( this ).val() );
			});

			// Increment quantity
			$('.featherlight-content #' + id ).on('click', '.btn-increment', function() {
				$('.featherlight-content #' + id + ' .custom-qty-input .input-text.qty').val( function( i, oldval ) {
					return parseInt( oldval, 10) + 1;
				}).trigger('change');
			});

			// Decrement quantity
			$('.featherlight-content #' + id ).on('click', '.btn-decrement', function() {
				$('.featherlight-content #' + id + ' .custom-qty-input .input-text.qty').val( function( i, oldval ) {
					if( parseInt( oldval, 10) > 1 ){
						return parseInt( oldval, 10) - 1;
					}
					else {
						return parseInt( oldval, 10);
					}
				}).trigger('change');
			});
		}
	});
});
