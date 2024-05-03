(function($) {
	$( document ).ready(
		function() {

			var container = $( '.acff-upgrade-pro-action' );
			if (container.length) {
				container.find( 'a.acff-dismiss-notice' ).click(
					function(e) {
						e.preventDefault();
						container.remove();
						$.post(
							fa.ajaxurl,
							{
								action: 'acff-upgrade-pro-dismiss',
								_n: $( this ).attr( 'data-nonce' )
							},
							function(result) {}
						);

					}
				);
			}
		}
	);
})( jQuery );
