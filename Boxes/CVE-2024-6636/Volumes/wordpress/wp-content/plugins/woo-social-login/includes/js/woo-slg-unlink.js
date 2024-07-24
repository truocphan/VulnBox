"use strict";
jQuery(document).ready( function($) {
	
	//Unlink Social media profile
	$(document).on('click','.woo-slg-social-unlink-profile',function(){
		var provider = $(this).attr('id');
		var data = { 
					action	:	'woo_slg_social_unlink_profile',
					provider	:	provider
				};
		var confirm_res = true;
		
		if( !provider.length ) {
			confirm_res = false;

			var confirm_box = confirm( WOOSlgUnlink.confirm_msg);
			if ( confirm_box == true) {
			    confirm_res = true;
			}
		}

		if ( confirm_res ) {
			
			//show loader
			jQuery('.woo-slg-login-loader').show();
			jQuery('.woo-social-login-profile').hide();
			
			jQuery.post( WOOSlgUnlink.ajaxurl,data,function(response){
				var result = jQuery.parseJSON( response );
				
				jQuery('.woo-slg-login-loader').hide();
				jQuery('.woo-social-login-profile').show();
				
				if(result.success =='1'){
					jQuery('.woo-social-login-profile').html(result.data);
					window.location.reload();
				}
				else if(result.failed=='1'){
					jQuery('.woo-slg-login-loader').hide();
					jQuery('.woo-social-login-profile').show();
				}
			});
		}
	});
});