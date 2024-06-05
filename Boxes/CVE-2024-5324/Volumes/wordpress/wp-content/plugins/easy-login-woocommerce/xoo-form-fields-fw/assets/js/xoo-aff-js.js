jQuery(document).ready(function($){



	(function(){

		//initialize datepicker
		$('.xoo-aff-input-date').each( function( index, dateEl ){
			var $dateEl = $(dateEl),
				dateData = $dateEl.data('date');
			$dateEl.datepicker( dateData );
		} );
		
	}());


	var all_states = JSON.parse(xoo_aff_localize.states);

	//-------------------- XXXXXXXXXXXXXXX -------------------- //

	var SelectCountry = function( $select ){
		
		var self = this;
		self.$selectCountry 	= $select; 
		self.id 				= self.$selectCountry.attr('name');
		self.$form 				= self.$selectCountry.closest( 'form' );
		self.$selectState 		= self.$form.find('.xoo-aff-states[data-country_field="'+self.id+'"]');
		self.$selectPhoneCode 	= self.$form.find('.xoo-aff-phone_code[data-country_field="'+self.id+'"]');

		//Methods
		self.states = self.$selectState.length ? self.states() : false;
		//Events
		self.$selectCountry.on( 'change', { country: self }, this.onChange );
		self.$selectCountry.trigger('change'); //on load

	}

	SelectCountry.prototype.onChange = function( event ){
		var self = event.data.country;
		if( self.states ){
			self.states.updateStateField( event );
		}

		if( self.$selectPhoneCode.length ){
			self.updatePhoneCodeField();
		}
	}

	//PhoneCode field handler
	SelectCountry.prototype.updatePhoneCodeField = function(){
		var country 	= this,
			$codeOption = country.$selectPhoneCode.find( 'option[data-country_code="'+country.$selectCountry.val()+'"]' );
		
		if( $codeOption ){
			$codeOption.prop('selected','selected');
		}

		country.$selectPhoneCode.trigger('change');
	}

	//State field handler
	SelectCountry.prototype.states = function(){

		var country 		= this,
			$selectState 	= country.$selectState,
			defaultValue 	= country.$selectState.data( 'default_state' );

		var Handler =  {

			init: function(){
				Handler.$statePlaceholder 	= $selectState.find('option[value="placeholder"]');
				Handler.$selectStateCont 	= $selectState.parent();
				Handler.$inputState 		= Handler.createStateInput();
			},

			getStates: function(){
				var states = all_states[ country.$selectCountry.val() ];
				return states === undefined || states.length === 0  ? null : states;
			},

			createStateInput: function(){
				return $( '<input type="text" />' )
					.prop( 'name', $selectState.attr('name') )
					.prop('placeholder', Handler.$statePlaceholder.html() )
					.addClass( $selectState.attr('class') );
			},

			updateStateField: function( event ){

				var country = event.data.country;

				//Remove all current states
				$selectState.find('option').not(Handler.$statePlaceholder).remove();

				if( country.$selectCountry.val() ){
					var active_states = Handler.getStates();

					if( !active_states ){
						Handler.$selectStateCont.find('.select2-container').remove();
						Handler.$selectStateCont.append( Handler.$inputState );
					}
					else{
						Handler.$inputState.remove();
						Handler.$selectStateCont.append( $selectState );
						$.each( active_states, function( state_key, label ){
							$selectState.append( '<option value="'+state_key+'">'+label+'</option>' );
						} )

						if( defaultValue ){
							Handler.$selectStateCont.find( 'option[value='+defaultValue+']' ).prop( 'selected', 'selected' );
						}

						$selectState.select2();
					}

					
				}

			}

		}

		Handler.init();

		return Handler;
	}

	$.each( $('select.xoo-aff-country'), function( index, el ){
		new SelectCountry( $(el));
	} );


	//-------------------- XXXXXXXXXXXXXXX -------------------- //

	var password_strength_meter = {

		/**
		 * Initialize strength meter actions.
		 */
		init: function() {
			$( document.body )
				.on(
					'keyup change',
					'input.xoo-aff-password[check_strength="yes"]',
					this.strengthMeter
				);
		},

		/**
		 * Strength Meter.
		 */
		strengthMeter: function() {
			var wrapper       = $(this).parents('.xoo-aff-group'),
				form 		  = wrapper.parents('form');
				submit        = form.length ? $( 'button[type="submit"]', form ) : false,
				field         = $(this),
				strength      = 1,
				passStrength  = $(this).attr('strength_pass').length ? $(this).attr('strength_pass') : xoo_aff_localize.password_strength.min_password_strength,
				fieldValue    = field.val();

			password_strength_meter.includeMeter( wrapper, field );

			strength = password_strength_meter.checkPasswordStrength( wrapper, field, passStrength );


			if (
				submit &&
				fieldValue.length > 0 &&
				strength < passStrength  &&
				-1 !== strength
			) {
				submit.attr( 'disabled', 'disabled' ).addClass( 'disabled' );
			} else {
				submit.removeAttr( 'disabled', 'disabled' ).removeClass( 'disabled' );
			}
		},

		/**
		 * Include meter HTML.
		 *
		 * @param {Object} wrapper
		 * @param {Object} field
		 */
		includeMeter: function( wrapper, field ) {
			var meter = wrapper.find( '.xoo-aff-password-strength' );

			if ( '' === field.val() ) {
				meter.hide();
				$( document.body ).trigger( 'xoo-aff-password-strength-hide' );
			} else if ( 0 === meter.length ) {
				wrapper.append( '<div class="xoo-aff-password-strength" aria-live="polite"></div>' );
				$( document.body ).trigger( 'xoo-aff-password-strength-added' );
			} else {
				meter.show();
				$( document.body ).trigger( 'xoo-aff-password-strength-show' );
			}
		},

		/**
		 * Check password strength.
		 *
		 * @param {Object} field
		 *
		 * @return {Int}
		 */
		checkPasswordStrength: function( wrapper, field, passStrength ) {
			var meter     = wrapper.find( '.xoo-aff-password-strength' ),
				hint      = wrapper.find( '.xoo-aff-password-hint' ),
				hint_html = '<small class="xoo-aff-password-hint">' + xoo_aff_localize.password_strength.i18n_password_hint + '</small>',
				strength  = wp.passwordStrength.meter( field.val(), wp.passwordStrength.userInputBlacklist() ),
				error     = '';

			// Reset.
			meter.removeClass( 'short bad good strong' );
			hint.remove();

			if ( meter.is( ':hidden' ) ) {
				return strength;
			}

			// Error to append
			if ( strength < passStrength ) {
				error = ' - ' + xoo_aff_localize.password_strength.i18n_password_error;
			}

			switch ( strength ) {
				case 0 :
					meter.addClass( 'short' ).html( pwsL10n['short'] + error );
					meter.after( hint_html );
					break;
				case 1 :
					meter.addClass( 'bad' ).html( pwsL10n.bad + error );
					meter.after( hint_html );
					break;
				case 2 :
					meter.addClass( 'bad' ).html( pwsL10n.bad + error );
					meter.after( hint_html );
					break;
				case 3 :
					meter.addClass( 'good' ).html( pwsL10n.good + error );
					break;
				case 4 :
					meter.addClass( 'strong' ).html( pwsL10n.strong + error );
					break;
				case 5 :
					meter.addClass( 'short' ).html( pwsL10n.mismatch );
					break;
			}

			return strength;
		}
	};

	password_strength_meter.init();

	if( $.fn.select2 ){
		$('select.xoo-aff-select_list , select.xoo-aff-country').each(function( key, el ){
			$(el).select2();
		});
	}


	if( $.fn.select2 ){

		function formatState (state) {

			if (!state.id) {
				return state.text;
			}

			var cc = state.element.getAttribute('data-country_code');
				cc = cc ? cc.toLowerCase() : cc;

			var $state = $( '<div class="xoo-aff-flag-cont"><span class="flag ' + cc +'"></span>' + '<span>' + state.text + '</span></div>' );

			return $state;

		};

		

		$('select.xoo-aff-phone_code').each(function( key, el ){
			$(el).select2({ templateResult: formatState, templateSelection: formatState, dropdownCssClass: "xoo-aff-select2-dropdown" });
		});
	}


	//Password toggle visiblity
	$( 'body' ).on( 'click', '.xoo-aff-pw-toggle', function(){

		var $password = $(this).closest('.xoo-aff-group').find('input.xoo-aff-password');

		$(this).toggleClass('active');

		if( $(this).hasClass('active') ){
			$password.attr('type','text');
			$(this).find('.xoo-aff-pwtog-hide').show();
			$(this).find('.xoo-aff-pwtog-show').hide();
		}
		else{
			$password.attr('type','password');
			$(this).find('.xoo-aff-pwtog-show').show();
			$(this).find('.xoo-aff-pwtog-hide').hide();
		}

	} )
})