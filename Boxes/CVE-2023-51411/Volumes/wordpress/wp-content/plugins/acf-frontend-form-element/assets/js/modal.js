(function($){
	$( document ).on(
		'click',
		'.fea-open-modal',
		function(e){
			var button = $( this );
			var modal = $( "#modal_" + button.data( 'modal' ) );
			if ( typeof acf !== 'undefined' ) {
				acf.do_action( 'append', modal );
			}
			modal.show().scrollTop( 0 );

		}
	);

	$( document ).on(
		'click',
		'.fea-close-modal',
		function(e){
			var button = $( this );
			var modal  = $( "#modal_" + button.data( 'modal' ) );

			modal.hide();
			if (typeof(clear) !== 'undefined') {
				modal.remove();
			}
		}
	);
	$( document ).on(
		'click',
		'.fea-modal',
		function(e){
			if (e.target == this) {
				$( this ).hide();
			}
		}
	);

})( jQuery );
