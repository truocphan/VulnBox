jQuery(document).ready(function( $ ) {
	$('[data-piotnetforms-dropdown-trigger]').click( function(e) {
	    e.preventDefault();
	    $(this).closest('[data-piotnetforms-dropdown]').find('[data-piotnetforms-dropdown-content]').toggle();
	}); 
});
