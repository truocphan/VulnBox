jQuery(document).ready(function($){

	function parse_notice( message, type = 'error' ){
		return xoo_el_localize.html.notice[ type ].replace( '%s', message );
	}

	var classReferral = {
		'xoo-el-login-tgr': 'login',
		'xoo-el-reg-tgr': 'register',
	}

	function getReferral(className){
		return classReferral[className] ? classReferral[className] : '';
	}

	function getFormsTrigger( $container = '' ){

		var isSingle = false;

		if( $container.length && $container.find('.xoo-el-section[data-section="single"]').length ){
			isSingle = true;
		}

		var formsTrigger = {
			'xoo-el-sing-tgr': 'single',
			'xoo-el-login-tgr': isSingle ? 'single' : 'login',
			'xoo-el-reg-tgr': isSingle ? 'single' : 'register',
			'xoo-el-lostpw-tgr': 'lostpw',
			'xoo-el-resetpw-tgr': 'resetpw',
			'xoo-el-forcereg-tgr': 'register',
			'xoo-el-forcelogin-tgr': 'login',
		}

		return formsTrigger;
	}


	class Container{

		constructor( $container ){
			this.$container = $container;
			this.$tabs 		= $container.find('ul.xoo-el-tabs').length ? $container.find( 'ul.xoo-el-tabs' ) : null;
			this.display 	= $container.hasClass('xoo-el-form-inline') ? 'inline' : 'popup';

			if( this.$container.attr('data-active') ){
				this.toggleForm( this.$container.attr('data-active') );
			}

			this.createFormsTriggerHTML();

			this.eventHandlers();
		}

		createFormsTriggerHTML(){
			var HTML = '<div style="display: none!important;">';
			$.each( getFormsTrigger(this.$container), function( triggerClass, type ){
				HTML += '<span class="'+triggerClass+'"></span>';
			} )
			HTML += '</div>';
			this.$container.append(HTML);
		}

		eventHandlers(){
			
			this.$container.on( 'submit', '.xoo-el-action-form', this.submitForm.bind(this) ) ;
			this.$container.on( 'click', '.xoo-el-edit-em', this.emailFieldEditClick.bind(this) );
			$( document.body ).on( 'xoo_el_form_submitted', this.singleFormProcess.bind(this) );
			this.formTriggerEvent();
		}


		emailFieldEditClick(e){
			this.toggleForm('single');
			this.$container.find('input[name="xoo-el-sing-user"]').val( $(e.currentTarget).siblings('input').val() ).focus().trigger('keyup');
		}


		formTriggerEvent(){

			var container 	= this,
				formsTrigger = getFormsTrigger(container.$container);

			$.each( formsTrigger, function( triggerClass, formType ){
				$( container.$container ).on( 'click', '.' + triggerClass, function(e){
					e.preventDefault();
					e.stopImmediatePropagation();
					container.toggleForm(formType, getReferral(triggerClass) );
				} )
			} );

		}


		toggleForm( formType, referral = '' ){

			this.$container.attr( 'data-active', formType );

			var $section 	= this.$container.find('.xoo-el-section[data-section="'+formType+'"]'),
				activeClass = 'xoo-el-active';

			//Setting section
			if( $section.length ){

				var $sectionForm = $section.find('form');

				this.$container.find('.xoo-el-section').removeClass( activeClass );
				$section.addClass( activeClass );
				$section.find('.xoo-el-notice').html('').hide();
				$section.find('.xoo-el-action-form').show();

				if( $sectionForm.length && referral && $sectionForm.find('input[name="_xoo_el_referral"]').length ){
					$sectionForm.find('input[name="_xoo_el_referral"]').val(referral);
				}

			}

			//Setting Tab
			if( this.$tabs ){	
				this.$tabs.find('li').removeClass( activeClass );
				if( this.$tabs.find('li[data-tab="'+formType+'"]').length ){
					this.$tabs.find('li[data-tab="'+formType+'"]').addClass( activeClass );
				}
			}

			$(document.body).trigger( 'xoo_el_form_toggled', [ formType, this, referral ] );

		}


		submitForm(e){

			e.preventDefault();

			var $form 			= $(e.currentTarget),
				$button 		= $form.find('button[type="submit"]'),
				$section 		= $form.parents('.xoo-el-section'),
				buttonTxt 		= $button.text(),
				$notice			= $section.find('.xoo-el-notice'),
				formType 		= $section.attr('data-section'),
				container 		= this;

			$button.html( xoo_el_localize.html.spinner ).addClass('xoo-el-processing');

			var form_data = $form.serialize() + '&action=xoo_el_form_action' + '&display=' + container.display;

			$.ajax({
				url: xoo_el_localize.adminurl,
				type: 'POST',
				data: form_data,
				complete: function( xhr, status ){
					$button.removeClass('xoo-el-processing').html(buttonTxt);
					if( ( status !== 'success' || !xhr.responseJSON || xhr.responseJSON.error === undefined  ) ){
						if( xoo_el_localize.errorLog === 'yes' ){
							$notice.html( parse_notice( "Plugin did not receive expected response. The action might not be completed. Some other plugin or code on your site is interferring with the plugin's functionality. Temporarily deactivate all other plugins to further confirm. To disable this message uncheck \"error log\" option from the settings . ", 'error' ) ).show();
						}
						else{
							location.reload();
						}
						
					}
				},
				success: function(response){

					//Unexpected response
					if( response.error === undefined ){
						console.log(response);
						//location.reload();
						return;
					}

					if( response.notice ){

						$notice.html(response.notice).show();

						//scrollbar position
						if( container.display === 'inline' ){
							$('html, body').animate({ scrollTop: $notice.offset().top - 100}, 500);
						}

					}

					if ( response.error === 0 ){
						
						if( response.redirect ){
							//Redirect
							setTimeout(function(){
								window.location = response.redirect;
							}, xoo_el_localize.redirectDelay );
						}
						else{
							$form.hide();
						}

						$form.trigger('reset');

						if( formType === 'resetpw' ){
							$form.add( '.xoo-el-resetpw-hnotice' ).remove();
						}

					}

					$( document.body ).trigger( 'xoo_el_form_submitted', [ response, $form, container ] );
					
				}
			})
		}


		singleFormProcess( e, response, $form, container ){

			if( this !== container ) return;

			if( response.field ){

				var $field = this.$container.find( response.field );

				if( $field.length ){

					this.toggleForm( $field.closest('.xoo-el-section').attr('data-section') );

					$field.closest('form').show();

					$field.val(response.fieldValue);

					$field.closest('.xoo-el-section').find('.xoo-el-notice').html(response.notice).show();

					var $fieldCont = $field.closest('.xoo-aff-group');

					if( !$fieldCont.find('.xoo-el-edit-em').length ){
						$fieldCont.addClass('xoo-el-block-edit');
						$(xoo_el_localize.html.editField).insertAfter($field);
					}

				}
			}

		}

	}


	class Popup{

		constructor( $popup ){
			this.$popup = $popup;
			this.eventHandlers();
		}

		eventHandlers(){

			this.$popup.on( 'click', '.xoo-el-close, .xoo-el-modal, .xoo-el-opac', this.closeOnClick.bind(this) );
			$( document.body ).on( 'xoo_el_form_submitted', this.onFormSubmitSuccess.bind(this) );
			this.$popup.on( 'click', '.xoo-el-action-btn', this.setScrollBarOnSubmit.bind(this) );
			$(window).on('hashchange load', this.openViaHash.bind(this) );
			this.triggerPopupOnClick(); //Open popup using link
		}

		triggerPopupOnClick(){

			$.each( getFormsTrigger(this.$popup), function( triggerClass, formType ){

				$( document.body ).on( 'click', '.' + triggerClass, function(e){

					if( $(this).parents( '.xoo-el-form-container' ).length ) return true; //Let container class handle

					e.preventDefault();
					e.stopImmediatePropagation();

					popup.toggle('show');

					if( $(this).attr( 'data-redirect' ) ){
						popup.$popup.find('input[name="xoo_el_redirect"]').val( $(this).attr('data-redirect') );
					}

					popup.$popup.find( '.'+triggerClass ).trigger('click');

					return false;

				})

			})

		}

		toggle( type ){
			var $els 		= this.$popup.add( 'body' ),
				activeClass = 'xoo-el-popup-active'; 

			if( type === 'show' ){
				$els.addClass(activeClass);
			}
			else if( type === 'hide' ){
				$els.removeClass(activeClass);
			}
			else{
				$els.toggleClass(activeClass);
			}

			$(document.body).trigger( 'xoo_el_popup_toggled', [ type ] );
		}

		closeOnClick(e){
			var elClassList = e.target.classList;
			if( elClassList.contains( 'xoo-el-close' ) || elClassList.contains('xoo-el-modal') || elClassList.contains('xoo-el-opac') ){
				this.toggle('hide');
			}
		}

		setScrollbarPosition( position ){
			this.$popup.find('.xoo-el-srcont').scrollTop = position || 0;
		}

		onFormSubmitSuccess( e, response, $form, container ){
			this.setScrollbarPosition();
		}

		setScrollBarOnSubmit(e){
			var invalid_els = $(e.currentTarget).closest('form').find('input:invalid');
			if( invalid_els.length === 0 ) return;
			this.setScrollbarPosition( invalid_els.filter(":first").closest('.xoo-aff-group').position().top );
		}

		openViaHash(){

	  		var hash = $(location).attr('hash');

	  		if( hash === '#login' || hash === '#register' ){

	  			this.toggle('show');

	  			//Clear hash
	  			var uri = window.location.toString(),
	  		 		clean_uri = uri.substring( 0, uri.indexOf("#") );
	 
	            window.history.replaceState(
	            	{},
	            	document.title, clean_uri
	            );
	  		}

	  		if( hash === '#login' ){
	  			this.$popup.find('.xoo-el-login-tgr').trigger('click');
	  		}
	  		else if( hash === '#register' ){
	  			this.$popup.find('.xoo-el-reg-tgr').trigger('click');
	  		}
    
		}

		
	}

	class Form{

		constructor( $form ){
			this.$form 	= $form;
		}

		eventHandlers(){

		}

	}

	var popup = null;

	//Popup
	if( $('.xoo-el-container').length ){
		popup = new Popup( $('.xoo-el-container') );
	}

	
	//Auto open popup
	if( xoo_el_localize.autoOpenPopup === 'yes' && localStorage.getItem( "xoo_el_popup_opened"  ) !== "yes" ){
		
		if( xoo_el_localize.autoOpenPopupOnce === "yes" ){
			localStorage.setItem( "xoo_el_popup_opened", "yes"  );
		}
		
		setTimeout(function(){
			popup.toggle('show');
		}, xoo_el_localize.aoDelay);
	}
	

	$('.xoo-el-form-container').each(function( key, el ){
		 new Container( $(el) );
	})

	//Trigger popup if reset field is active
	if( $('form.xoo-el-form-resetpw').length ){
		if( $('.xoo-el-form-inline').length ){
			$([document.documentElement, document.body]).animate({
				scrollTop: $(".xoo-el-form-inline").offset().top
			}, 500);
		}
		else{
			if( popup ){
				popup.toggle('show');
			}
		}
	}


	if( $( 'body.woocommerce-checkout' ).length && $('.xoo-el-form-inline').length && $( 'a.showlogin' ).length ){
  		var $inlineForm = $('.xoo-el-form-inline');
  		$inlineForm.hide();
  		$( document.body ).on( 'click', 'a.showlogin', function(){
  			$inlineForm.slideToggle();
  			$inlineForm.find('.xoo-el-login-tgr').trigger('click');
  		} );	
  	}


  	if( xoo_el_localize.loginClass && $( '.'+xoo_el_localize.loginClass ).length ){
  		$( '.'+xoo_el_localize.loginClass ).on( 'click', function(e){
  			e.preventDefault();
  			e.stopImmediatePropagation();
  			$( '.xoo-el-login-tgr' ).trigger('click');
  		} );
  	}

  	if( xoo_el_localize.registerClass && $( '.'+xoo_el_localize.registerClass ).length ){
  		$( '.'+xoo_el_localize.registerClass ).on( 'click', function(e){
  			e.preventDefault();
  			e.stopImmediatePropagation();
  			$( '.xoo-el-reg-tgr' ).trigger('click');
  		} );
  	}


})