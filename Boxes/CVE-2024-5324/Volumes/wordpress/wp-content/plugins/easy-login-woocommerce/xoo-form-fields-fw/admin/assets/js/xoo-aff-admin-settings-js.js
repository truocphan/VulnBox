jQuery(document).ready(function($){
	'use strict';

	//Initialize Color Picker
	$(function(){
		$('.color-field').wpColorPicker();
	});


	//Sidebar JS
	$(function(){
		var show_class 	= 'xoo-sidebar-show';
		var sidebar 	= $('.xoo-sidebar');
		var togglebar 	= $('.xoo-sidebar-toggle');

		//Show / hide sidebar
		if(localStorage.xoo_admin_sidebar_display){
			if(localStorage.xoo_admin_sidebar_display == 'shown'){
				sidebar.removeClass(show_class);
			}
			else{
				sidebar.addClass(show_class);
			}
			on_sidebar_toggle();
		}

		togglebar.on('click',function(){
			sidebar.toggleClass(show_class);
			on_sidebar_toggle();
		})

		function on_sidebar_toggle(){
			if(sidebar.hasClass(show_class)){
				togglebar.text('Show');
				var display = "hidden";
			}else{
				togglebar.text('Hide');
				var display = "shown";
			}
			localStorage.setItem("xoo_admin_sidebar_display",display);
		}
	});


	//Media

	function renderMediaUploader(upload_btn) {
	 
	    var file_frame, image_data;
	 
	    /**
	     * If an instance of file_frame already exists, then we can open it
	     * rather than creating a new instance.
	     */
	    if ( undefined !== file_frame ) {
	 
	        file_frame.open();
	        return;
	 
	    }
	 
	    /**
	     * If we're this far, then an instance does not exist, so we need to
	     * create our own.
	     *
	     * Here, use the wp.media library to define the settings of the Media
	     * Uploader. We're opting to use the 'post' frame which is a template
	     * defined in WordPress core and are initializing the file frame
	     * with the 'insert' state.
	     *
	     * We're also not allowing the user to select more than one image.
	     */
	    file_frame = wp.media.frames.file_frame = wp.media({
	        frame:    'post',
	        state:    'insert',
	        multiple: false
	    });
	 
	    /**
	     * Setup an event handler for what to do when an image has been
	     * selected.
	     *
	     * Since we're using the 'view' state when initializing
	     * the file_frame, we need to make sure that the handler is attached
	     * to the insert event.
	     */
	    file_frame.on( 'insert', function() {
	 	
	        // Read the JSON data returned from the Media Uploader
   		 	var json = file_frame.state().get( 'selection' ).first().toJSON();

   		 	upload_btn.siblings('.xoo-upload-url').val(json.url);
   		 	upload_btn.siblings('.xoo-upload-title').html(json.filename);
   		
	 
	    });
	 
	    // Now display the actual file_frame
	    file_frame.open();
 
	}





	
    $( '.xoo-upload-icon' ).on( 'click', function( evt ) {
        // Stop the anchor's default behavior
        evt.preventDefault();

        // Display the media uploader
        renderMediaUploader($(this));

    });
 
   


    //Get media uploaded name
	$('.xoo-upload-url').each(function(){
		var media_url = $(this).val();
		if(!media_url) return true; // Skip to next if no value is set

		var index = media_url.lastIndexOf('/') + 1;
		var media_name = media_url.substr(index);

		$(this).siblings('.xoo-upload-title').html(media_name);
	})


	//Remove uploaded file
	$('.xoo-remove-media').on('click',function(){
		$(this).siblings('.xoo-upload-url').val('');
		$(this).siblings('.xoo-upload-title').html('');
	})


	//Disable recaptcha fields if disabled
	$('input[name="xoo-el-advanced-options[m-en-recaptcha]"]').on('change',function(){
		var fields = $('input[name="xoo-el-advanced-options[m-en-recaptcha-secretkey]"] , input[name="xoo-el-advanced-options[m-en-recaptcha-sitekey]"] ').parents('tr');
		if( $(this).is(':checked') ){
			fields.show();
		}
		else{
			fields.hide();
		}
	}).trigger('change');

	//How to toggle
	$('a.xoo-el-howto-toggle').on('click',function(){
		$(this).parents('.xoo-el-howto-container').toggleClass('xoo-el-howto-active');
	})



	$('body').on( 'click' , 'a.xoo-el-otp-dwnld',function(){

		var $noticeEl 	= $('.xoo-el-notice'),
			_t 			= $(this);

		$(this).html('Downloading..please wait..').css('pointer-events','none');

		$.ajax({
			url: xoo_aff_admin_settings_localize.adminurl,
			type: 'POST',
			data: {
				'action': 'download_otp_plugin',
			},
			success: function(response){
				if( response.notice ){
					_t.hide();
					$noticeEl.html(response.notice).show();
				}
			}
		});

	})

});
