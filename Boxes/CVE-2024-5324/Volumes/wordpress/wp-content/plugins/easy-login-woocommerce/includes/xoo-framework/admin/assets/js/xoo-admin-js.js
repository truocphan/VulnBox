jQuery(document).ready(function($){

	//Form reset
	$('.xoo-as-form-reset').click(function(e){
		if( !confirm( 'Are you sure?' ) )
			e.preventDefault();
	})

	//Toggle pro
	$('.xoo-as-pro-toggle').click(function(e){
		$('.xoo-settings-container').toggleClass('xoo-as-disable-pro');
	})

	$('.xoo-settings-container').addClass('xoo-as-disable-pro');

	var sectionScrollPositions = {}

	//Setting default position to 0
	$('ul.xoo-sc-tabs li').each( function(){
		sectionScrollPositions[ $(this).data('tab') ] = $('.xoo-sc-tabs').offset().top;

	} );


	var firstClick = true;


	//Switch Tabs
	$('ul.xoo-sc-tabs li').click(function(){

		if( !firstClick ){
			sectionScrollPositions[$('ul.xoo-sc-tabs li.xoo-sct-active').data('tab')] = $(window).scrollTop();
		}

		$('ul.xoo-sc-tabs li, .xoo-sc-tab-content').removeClass('xoo-sct-active');
		$(this).addClass('xoo-sct-active');
		$(this).parents('.xoo-settings-container').attr('active-tab',$(this).data('tab'));
		$('.xoo-sc-tab-content[data-tab="'+$(this).data('tab')+'"]').addClass('xoo-sct-active');

		if( !firstClick ){
			$(window).scrollTop( sectionScrollPositions[ $(this).data('tab') ] );
		}
		
		firstClick = false;

	})

	$('ul.xoo-sc-tabs li:nth-child(1)').trigger('click');

	$('.xoo-as-form').on( 'submit', function(e){

		e.preventDefault();

		$button = $(this).find('.xoo-as-form-save');
		$button.text( 'Saving....' );

		var data = {
			'form': $(this).serialize(),
			'action': 'xoo_admin_settings_save',
			'xoo_ff_nonce': xoo_admin_params.nonce,
			'slug': xoo_admin_params.slug
		}

		$.ajax({
			url: xoo_admin_params.adminurl,
			type: 'POST',
			data: data,
			success: function(response){
				$button.text('Settings Saved');
				setTimeout(function(){
					$button.text( 'Save' )
				},5000)
			}
		});

	})



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


	//Initialize color picker
	$('.xoo-as-color-input').wpColorPicker();

	//initialize sortable
	$('.xoo-as-sortable-list').each( function( index, sortEl ){
		var $sortEl = $(sortEl),
			sortData = $sortEl.data('sort');
		$sortEl.sortable( sortData );
	} );


	$( 'select[data-select2box="yes"]' ).each(function(index, el){
		var $el = $(el);
		$el.select2({
			multiple: $el.attr('data-multiple')
		});
	});


	$('.xoo-as-exim').on( 'click', function(){
		$(this).toggleClass('xoo-as-active');
	} );


	//On export settings click
	$('.xoo-as-setexport').on( 'click', function(){
		var $form = $(this).closest('form.xoo-as-form');
		$('.xoo-as-exim').removeClass('xoo-as-active');
		$('body').addClass('xoo-as-exmodal-active');
		$('.xoo-as-excont textarea').val( JSON.stringify($form.serializeArray()) ).select();

		$('.xoo-as-impcont').hide();
		$('.xoo-as-excont').show();
	} );


	//Close import/export modal
	$('.xoo-as-exipclose').on( 'click', function(){
		$('body').removeClass('xoo-as-exmodal-active');
	} );



	/*$('button.xoo-as-run-import').on( 'click', function(){

		var textarea = $(this).siblings('textarea'),
			settings = textarea.val();

		if( !settings ) return;

		if( !confirm( 'This will override your current settings. Are you sure?' ) ) return;

		$(this).addClass('xoo-as-processing');

		var data = JSON.parse(settings);

		var fields = {};

		$.each( data, function( index, field ){

			if( fields[ field.name ] ){
				if( Array.isArray( fields[ field.name ]  ) ){
					fields[ field.name ].push( field.value ); 
				}
				else{
					fields[ field.name ] = [
						fields[ field.name ],
						field.value
					];
				}
			}
			else{
				fields[ field.name ] = field.value;
			}

		} )

		console.log(fields);

		$.each( fields, function( id, value ){

			var $el = $('[name="'+id+'"]');

			if( !$el.length ) return;

			var $settingCont = $el.closest( '.xoo-as-setting' );

			if( !$settingCont.length ) return;

			var type = $settingCont.attr('data-setting');

			if( type === 'checkbox' ){ //switch gives two values
				value = value[1];
			}

			if( type === 'checkbox_list' || type === 'checkbox' ){
				$settingCont.find('input[type="checkbox"]').prop('checked', false);
			}
			else if( type === 'radio' ){
				$settingCont.find( 'input[type="radio"]' ).prop('checked', false);
			}

			if( Array.isArray( value ) && type !== 'select' ){

				$.each( value, function( index, optionValue ){

					var $option = $settingCont.find('[value="'+optionValue+'"]');

					if( !$option.length ) return;

					if( type === 'checkbox_list' ){
						$option.prop('checked', true );
					}

				} );
			
			}
			else{

				if( type === 'checkbox' || type === 'radio'){
					$settingCont.find('input[value="'+value+'"]').prop('checked', true);
				}
				else{
					$el.val( value );
				}
				
			}

			$el.trigger('change');

		} )

		$(this).removeClass('xoo-as-processing');
		textarea.val('');
		$('.xoo-as-imported').addClass('xoo-as-active');
	} );*/


	//On import settings click
	$('.xoo-as-setimport').on( 'click', function(){
		$('.xoo-as-exim, .xoo-as-imported').removeClass('xoo-as-active');
		$('.xoo-as-impcont').show();
		$('.xoo-as-excont').hide();
		$('body').addClass('xoo-as-exmodal-active');
	} );


	$('.xoo-as-run-export').click( function(){

		$('.xoo-as-expdone').hide();

		var options = [];

		$('.xoo-as-expcheck input[type="checkbox"]:checked').each( function( index, el ){
			var $el = $(el);
			options.push($el.attr('value'));
		} )

		if( !options.length ) return;

		var $button = $('button.xoo-as-run-export ');

		$button.addClass('xoo-as-processing');
		$button.text( 'Please wait....' );


		var data = {
			'action': 'xoo_admin_settings_export',
			'xoo_ff_nonce': xoo_admin_params.nonce,
			'slug': xoo_admin_params.slug,
			'options': options
		}

		$.ajax({
			url: xoo_admin_params.adminurl,
			type: 'POST',
			data: data,
			success: function(response){
				$button.text('Export Success');
				

				setTimeout(function(){
					$button.text( 'Export' )
				},5000)
				$('.xoo-as-expdone').show();
				$('.xoo-as-expdone textarea').val(JSON.stringify(response)).select();
			}
		});

	} );

	$('button.xoo-as-run-import').click( function(){

		if( !confirm( 'This will override your current settings. Are you sure?' ) ) return;

		var textValue 	= $('.xoo-as-impcont textarea').val(),
			$button  	= $(this);

		$button.addClass('xoo-as-processing');
		$button.text( 'Please wait....' );

		var data = {
			'action': 'xoo_admin_settings_import',
			'xoo_ff_nonce': xoo_admin_params.nonce,
			'slug': xoo_admin_params.slug,
			'import': textValue
		}

		$.ajax({
			url: xoo_admin_params.adminurl,
			type: 'POST',
			data: data,
			success: function(response){
				$('.xoo-as-imported').addClass('xoo-as-active');
				$('.xoo-as-impcont textarea').val('');
				$button.text('Import Success');
				setTimeout(function(){
					$button.text( 'Import' );
					location.reload();
				},3000)
			}
		});

	})

})