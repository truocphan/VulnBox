jQuery(document).ready(function($) {
	$(document).on("click",".card-update",function(e) {
		zeusToken.getToken(function(zeus_token_response_data) {
			if( !zeus_token_response_data['result'] ) {
				alert(zeusToken.getErrorMessage(zeus_token_response_data['error_code']));
				e.preventDefault();
				e.stopPropagation();
				return false;
			} else {
				$("#member-card-info").submit();
			};
		});
	});
});
