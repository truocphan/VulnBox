jQuery(document).ready(function(){
	jQuery("#social_login_button").hide();
	jQuery("#social_login").click(function(){
		jQuery("#social_login_button").slideToggle( "slow");
	});
	jQuery("#social_login").click(function(e){
		e.preventDefault();
	});	jQuery("#reset_default_all").click(function(e){		jQuery("#psl_facebook_id").val('');				jQuery("#psl_twitter_key").val('');				jQuery("#psl_twitter_secret").val('');				jQuery("#psl_google_id").val('');				jQuery("#psl_google_secret").val('');				jQuery('#psl_facebook_enable').prop('checked', false);				jQuery('#psl_twitter_enable').prop('checked', false);				jQuery('#psl_google_enable').prop('checked', false);	});
		
});

