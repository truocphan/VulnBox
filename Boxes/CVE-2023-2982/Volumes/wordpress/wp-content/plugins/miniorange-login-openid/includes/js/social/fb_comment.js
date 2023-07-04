jQuery(window).load(function () {
	moOpenidLoadCommentScript();
	moOpenIDShowCommentForms();

	if(document.getElementById("moopenid_social_comment_default")) {
		jQuery("#moopenid_comment_form_default").attr("style","display:block");
		jQuery("#moopenid_social_comment_default").addClass("mo_openid_selected_tab");
	} else if(document.getElementById("moopenid_social_comment_fb")) {
		jQuery("#moopenid_comment_form_fb").attr("style","display:block");
		jQuery("#moopenid_social_comment_fb").addClass("mo_openid_selected_tab");
	} else if(document.getElementById("moopenid_social_comment_disqus")){
		jQuery("#moopenid_comment_form_disqus").attr("style","display:block");
		jQuery("#moopenid_social_comment_disqus").addClass("mo_openid_selected_tab");
	}

	jQuery("#moopenid_social_comment_fb").click(function(){
		jQuery("#moopenid_comment_form_fb").attr("style","display:block");
		jQuery("#moopenid_comment_form_disqus").attr("style","display:none");
		jQuery("#moopenid_comment_form_default").attr("style","display:none");
		jQuery("#moopenid_social_comment_fb").addClass("mo_openid_selected_tab");
		jQuery("#moopenid_social_comment_default").removeClass("mo_openid_selected_tab");
		jQuery("#moopenid_social_comment_disqus").removeClass("mo_openid_selected_tab");
	});
	jQuery("#moopenid_social_comment_default").click(function(){
		jQuery("#moopenid_comment_form_fb").attr("style","display:none");
		jQuery("#moopenid_comment_form_disqus").attr("style","display:none");
		jQuery("#moopenid_comment_form_default").attr("style","display:block");
		jQuery("#moopenid_social_comment_fb").removeClass("mo_openid_selected_tab");
		jQuery("#moopenid_social_comment_default").addClass("mo_openid_selected_tab");
		jQuery("#moopenid_social_comment_disqus").removeClass("mo_openid_selected_tab");
	});
	jQuery("#moopenid_social_comment_disqus").click(function(){
		jQuery("#moopenid_comment_form_fb").attr("style","display:none");
		jQuery("#moopenid_comment_form_disqus").attr("style","display:block");
		jQuery("#moopenid_comment_form_default").attr("style","display:none");
		jQuery("#moopenid_social_comment_fb").removeClass("mo_openid_selected_tab");
		jQuery("#moopenid_social_comment_default").removeClass("mo_openid_selected_tab");
		jQuery("#moopenid_social_comment_disqus").addClass("mo_openid_selected_tab");
	});
});

function moOpenidLoadCommentScript(){
	var d;
	d = document.createElement("div");
	d.id = "fb-root";
	document.body.appendChild(d);
	var sfb = document.createElement("script");
	sfb.innerHTML = '(function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0];if (d.getElementById(id)) return;js = d.createElement(s); js.id = id;js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.5";fjs.parentNode.insertBefore(js, fjs);}(document, "script", "facebook-jssdk"));';
	document.body.appendChild(sfb);
}