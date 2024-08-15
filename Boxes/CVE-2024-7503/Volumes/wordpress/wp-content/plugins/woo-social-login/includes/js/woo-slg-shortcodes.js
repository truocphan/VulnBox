"use strict";

var wooslged;
var wooslgurl;
//add the button to tinymce editor
(function() {
    tinymce.create('tinymce.plugins.woo_social_login', {
    	
    	init : function(ed, url) {
			
			ed.addButton('woo_social_login', {
				
				title : 'WOO Social Login',
				image : url+'/shortcode-icon.png',
				onclick : function() {
					
                    jQuery( '#woo_slg_redirect_url' ).val( '' );
                    jQuery( '#woo_slg_show_on_page' ).attr( 'checked', false );
					
					var popupcontent = jQuery( '.woo-slg-popup-content' );
					popupcontent.fadeIn();
					jQuery( '.woo-slg-popup-overlay' ).fadeIn();
 				}
			});
    	},
        createControl : function(n, cm) {
			return null;
		}
	});
	tinymce.PluginManager.add('woo_social_login', tinymce.plugins.woo_social_login);

})();

// JavaScript Document
jQuery(document).ready(function($) {
	
	// Set the select2 for setting page
    $("#woo-slg-selected-networks").select2({width:'100%'});
	
	//close popup window
	$( document ).on( 'click', '.woo-slg-close-button, .woo-slg-popup-overlay', function(){
		
		$( '.woo-slg-popup-overlay' ).fadeOut();
        $( '.woo-slg-popup-content' ).fadeOut();
        
	});
	
	$( document ).on( 'click', '#woo_slg_insert_shortcode', function(){
		
		var wooslgshortcode 	= 	'woo_social_login';
		var title 				=	$( '#woo_slg_title' ).val();
		var redirect_url 		=	$( '#woo_slg_redirect_url' ).val();
		var showonpage			=	$( '#woo_slg_show_on_page');
		var expand_colapse		=	$( '#woo_slg_enable_expand_collapse').val();
		var social_networks		=	$( '#woo-slg-selected-networks').val();
		var wooslgshortcodestr 	= 	'';

		wooslgshortcodestr	+= '['+wooslgshortcode;
		if(title != '') {
			wooslgshortcodestr	+= ' title="'+title+'"';
		}
		if( social_networks != '' && social_networks !== null ){
			wooslgshortcodestr	+= ' networks="'+social_networks+'"';
		}
		if( showonpage.is(':checked') ) {
			wooslgshortcodestr	+= ' showonpage="true"';
		}
		if(redirect_url != '') {
			wooslgshortcodestr	+= ' redirect_url="'+redirect_url+'"';
		}
		if( expand_colapse != '' ) {
			wooslgshortcodestr	+= ' expand_collapse="'+expand_colapse+'"';
		}
		wooslgshortcodestr	+= '][/'+wooslgshortcode+']';
		
        window.send_to_editor( wooslgshortcodestr );
  		jQuery('.woo-slg-popup-overlay').fadeOut();
		jQuery('.woo-slg-popup-content').fadeOut();
		
	});
	
});

//switch wordpress editor to visual mode
function wooSlgSwitchDefaultEditorVisual() {
	if (jQuery('#content').hasClass('html-active')) {
		switchEditors.go(editor, 'tinymce');
	}
}