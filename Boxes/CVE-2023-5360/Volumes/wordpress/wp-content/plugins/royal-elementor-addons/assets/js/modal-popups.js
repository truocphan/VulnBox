( function( $, elementor ) {

	"use strict";

	var WprPopups = {

		init: function() {
			$(document).ready(function() {
				if ( ! $( '.wpr-template-popup' ).length || WprPopups.editorCheck() ) {
					return;
				}

				WprPopups.openPopupInit();
				WprPopups.closePopupInit();
			});
		},

		openPopupInit: function() {
			$( '.wpr-template-popup' ).each( function() {
				var popup = $(this),
					popupID = WprPopups.getID( popup );

				if ( ! WprPopups.checkAvailability( popupID ) ) {
					return;
				}

				if ( ! WprPopups.checkStopShowingAfterDate( popup ) ) {
					return;
				}

				// Set Local Storage
				WprPopups.setLocalStorage( popup, 'show' );

				// Get Settings
				var getLocalStorage = JSON.parse( localStorage.getItem( 'WprPopupSettings' ) ),
					settings = getLocalStorage[ popupID ];

				if ( ! WprPopups.checkAvailableDevice( popup, settings ) ) {
					return false;
				}

				// Trigger Button Init
				WprPopups.popupTriggerInit( popup );

				// Page Load
				if ( 'load' === settings.popup_trigger ) {
					var loadDelay = settings.popup_load_delay * 1000;

					$(window).on( 'load', function() {
						setTimeout( function() {
							WprPopups.openPopup( popup, settings );
						}, loadDelay );
					});

				// Page Scroll
				} else if ( 'scroll' === settings.popup_trigger ) {
					$(window).on( 'scroll', function() {
						var scrollPercent = $(window).scrollTop() / ($(document).height() - $(window).height()),
							scrollPercent = Math.round( scrollPercent * 100 );

						if ( scrollPercent >= settings.popup_scroll_progress && ! popup.hasClass( 'wpr-popup-open' ) ) {
							WprPopups.openPopup( popup, settings );
						}
					});

				// Scroll to Element
				} else if ( 'element-scroll' === settings.popup_trigger ) {
					$(window).on( 'scroll', function() {
						var element = $( settings.popup_element_scroll ),
							ScrollBottom = $(window).scrollTop() + $(window).height();

						if ( ! element.length ) {
							return;
						}

						if ( element.offset().top < ScrollBottom && ! popup.hasClass( 'wpr-popup-open' ) ) {
							WprPopups.openPopup( popup, settings );
						}
					});

				// Specific Date
				} else if ( 'date' === settings.popup_trigger ) {
					var nowDate   = Date.now(),
						startDate = Date.parse( settings.popup_specific_date );

					if ( startDate < nowDate ) {

						setTimeout( function() {
							WprPopups.openPopup( popup, settings );
						}, 1000 );
					}

				// User Inactivity
				} else if ( 'inactivity' === settings.popup_trigger ) {
					var idleTimer = null,
						inactivityTime = settings.popup_inactivity_time * 1000;

					$( '*' ).bind( 'mousemove click keyup scroll resize', function () {
						if ( popup.hasClass( 'wpr-popup-open' ) ) {
							return;
						}

						// Reset Timer
						clearTimeout( idleTimer );

						// Open if Inactive
						idleTimer = setTimeout( function() { 
							WprPopups.openPopup( popup, settings );
						}, inactivityTime );
					});

					$( 'body' ).trigger( 'mousemove' );

				// User Exit Intent
				} else if ( 'exit' === settings.popup_trigger ) {
					$(document).on( 'mouseleave', 'body', function( event ) {
						if ( ! popup.hasClass( 'wpr-popup-open' ) ) {
							WprPopups.openPopup( popup, settings );
						}
					} );

				// Custom Trigger
				} else if ( 'custom' === settings.popup_trigger ) {
					$( settings.popup_custom_trigger ).on( 'click', function() {
						WprPopups.openPopup( popup, settings );
					});

					$( settings.popup_custom_trigger ).css( 'cursor', 'pointer' );
				}

				// Enable Scrollbar
				if ( '0px' !== popup.find('.wpr-popup-container-inner').css('height') ) {
					const ps = new PerfectScrollbar(popup.find('.wpr-popup-container-inner')[0], {
						suppressScrollX: true
					});
				}
			});
		}, // End openPopup

		openPopup: function( popup, settings ) {
			if ( 'notification' === settings.popup_display_as ) {
				popup.addClass( 'wpr-popup-notification' );

				setTimeout(function() {
					$( 'body' ).animate({
						'padding-top' : popup.find( '.wpr-popup-container' ).outerHeight() +'px'
					}, settings.popup_animation_duration * 1000, 'linear' );
				}, 10 );
			}

			// Disable Page Scroll
			if ( settings.popup_disable_page_scroll && 'modal' === settings.popup_display_as ) {
				$( 'body' ).css( 'overflow', 'hidden' );
			}

			// Open Popup
			popup.addClass( 'wpr-popup-open' ).show();
			popup.find( '.wpr-popup-container' ).addClass( 'animated '+ settings.popup_animation );

            // goga
            $(window).trigger('resize');

			// Overlay Fade In
			$( '.wpr-popup-overlay' ).hide().fadeIn();

			// Close Button Show Up Delay
			popup.find( '.wpr-popup-close-btn' ).css( 'opacity', '0' );
			setTimeout(function() {
				popup.find( '.wpr-popup-close-btn' ).animate({
					'opacity' : '1'
				}, 500 );
			}, settings.popup_close_button_display_delay * 1000 );


			// Close Automatically
			if ( false !== settings.popup_automatic_close_switch ) {
				setTimeout(function() {
					WprPopups.closePopup( popup );
				}, settings.popup_automatic_close_delay * 1000 );
			}
		}, // End openPopup

		closePopupInit: function() {
			// Close Button
			$( '.wpr-popup-close-btn' ).on( 'click', function() {
				WprPopups.closePopup( $(this).closest( '.wpr-template-popup' ) );
			});

			// Overlay Click
			$( '.wpr-popup-overlay' ).on( 'click', function() {
				var popup = $(this).closest( '.wpr-template-popup' ),
					popupID = WprPopups.getID( popup ),
					settings = WprPopups.getLocalStorage( popupID );

				if ( false == settings.popup_overlay_disable_close ) {
					WprPopups.closePopup( popup );
				}
			});

			// ESC Key Press
			$(document).on( 'keyup', function( event ) {
				var popup = $( '.wpr-popup-open' );

				if ( popup.length ) {
					var	popupID = WprPopups.getID( popup ),
						settings = WprPopups.getLocalStorage( popupID );

					if ( 27 == event.keyCode && false == settings.popup_disable_esc_key ) {
						WprPopups.closePopup( popup );
					}
				}
			});
		},

		closePopup: function( popup, ) {
			var popupID = WprPopups.getID( popup ),
				settings = WprPopups.getLocalStorage( popupID );

			// Notification
			if ( 'notification' === settings.popup_display_as ) {
				$( 'body' ).css( 'padding-top', 0 );
			}

			// Update Local Storage
			WprPopups.setLocalStorage( popup, 'hide' );

			// Close Pupup
			if ( 'modal' === settings.popup_display_as ) {
				popup.fadeOut();
			} else {
				popup.hide();
			}

			// Enable Page Scrolling
			$( 'body' ).css( 'overflow', 'visible' );
			
            // goga
            $(window).trigger('resize');
		},

		popupTriggerInit: function( popup ) {
			var popupTrigger = popup.find( '.wpr-popup-trigger-button' );

			if ( ! popupTrigger.length ) {
				return;
			}

			popupTrigger.on( 'click', function() {
				// Get Settings
				var settings = JSON.parse( localStorage.getItem( 'WprPopupSettings') ) || {};

				var popupTriggerType = $(this).attr( 'data-trigger' ),
					popupShowDelay = $(this).attr( 'data-show-delay'),
					popupRedirect = $(this).attr( 'data-redirect'),
					popupRedirectURL = $(this).attr( 'data-redirect-url'),
					popupID = WprPopups.getID( popup );

				if ( 'close' === popupTriggerType ) {
					settings[popupID].popup_show_again_delay = parseInt( popupShowDelay, 10 );
					settings[popupID].popup_close_time = Date.now();
				} else if ( 'close-permanently' === popupTriggerType ) {
					settings[popupID].popup_show_again_delay = parseInt( popupShowDelay, 10 );
					settings[popupID].popup_close_time = Date.now();
				} else if ( 'back' === popupTriggerType ) {
					window.history.back();
				}

				WprPopups.closePopup( popup );

				// Save Settings in Browser
				localStorage.setItem( 'WprPopupSettings', JSON.stringify( settings ) );

				if ( 'back' !== popupTriggerType && 'yes' === popupRedirect ) {
					setTimeout(function() {
						window.location.href = popupRedirectURL;
					}, 100);
				}
			});

		}, // End popupTriggerInit

		getLocalStorage: function( id ) {
			var getLocalStorage = JSON.parse( localStorage.getItem( 'WprPopupSettings' ) );

			if ( null == getLocalStorage ) {
				return false;
			}

			// Get Settings
			var settings = getLocalStorage[ id ];

			if ( null == settings ) {
				return false;
			}

			return settings;
		},

		setLocalStorage: function( popup, display ) {
			var popupID = WprPopups.getID( popup );

			// Parse Settings
			var dataSettings = JSON.parse( popup.attr( 'data-settings' ) ),
				settings = JSON.parse( localStorage.getItem( 'WprPopupSettings') ) || {};

			// Merge With Defaults
			settings[popupID] = dataSettings;

			// Set Close Time
			if ( 'hide' === display ) {
				settings[popupID].popup_close_time = Date.now();
			} else {
				settings[popupID].popup_close_time = false;
			}

			// Save Settings in Browser
			localStorage.setItem( 'WprPopupSettings', JSON.stringify( settings ) );
		},

		checkStopShowingAfterDate: function( popup ) {
			var settings = JSON.parse( popup.attr( 'data-settings' ) );

			// Current Date
			var currentDate = Date.now();

			// Stop Showing after Date
			if ( 'yes' === settings.popup_stop_after_date ) {
				if ( currentDate >= Date.parse( settings.popup_stop_after_date_select ) ) {
					return false;
				}
			}

			return true;
		},

		checkAvailability: function( id ) {
			var popup = $( '#wpr-popup-id-'+ id ),
				dataSettings = JSON.parse( popup.attr( 'data-settings' ) ),
				currentURL = window.location.href;

			if ( 'yes' === dataSettings.popup_show_via_referral && -1 === currentURL.indexOf('wpr_templates=user-popup') ) {
				if ( currentURL.indexOf( dataSettings.popup_referral_keyword ) == -1 ) {
					return;
				}
			}

			// If Storage not set, continue
			if ( false === WprPopups.getLocalStorage( id ) ) {
				return true;
			}

			// Popup Trigger
			var trigger = popup.find( '.wpr-popup-trigger-button' ),
				triggerShowDelay = trigger.attr( 'data-show-delay' );

			// Current Date
			var currentDate = Date.now();

			// Get Settings
			var settings = WprPopups.getLocalStorage( id );

			// If delay has been changed
			if ( triggerShowDelay ) {

				var permanent = true;

				trigger.each(function() {
					var delay = $(this).attr( 'data-show-delay' );

					if ( settings.popup_show_again_delay == parseInt( delay, 10 ) ) {
						permanent = false;
					}
				});

				if ( true === permanent ) {
					return true;
				}
			} else {
				if ( settings.popup_show_again_delay != dataSettings.popup_show_again_delay ) {
					return true;
				}
			}

			// Get Dates
			var closeDate = settings.popup_close_time || 0,
				showDelay = parseInt( settings.popup_show_again_delay, 10 );

			if ( closeDate + showDelay >= currentDate ) {
				return false;
			} else {
				return true;
			}
		},

		checkAvailableDevice: function( popup, settings ) {//TODO: Add all 7 device support
			var viewport = $( 'body' ).prop( 'clientWidth' );

			if ( viewport > 1024 ) {
				return Boolean(settings.popup_show_on_device);
			} else if ( viewport > 768 ) {
				return Boolean(settings.popup_show_on_device_tablet);
			} else {
				return Boolean(settings.popup_show_on_device_mobile);
			}
		},

		getID: function( popup ) {
			var id = popup.attr( 'id' );

			return id.replace( 'wpr-popup-id-', '' );
		},

		// Editor Check
		editorCheck: function() {
			return $( 'body' ).hasClass( 'elementor-editor-active' ) ? true : false;
		}
	} // End WprPopups

	// Init
	WprPopups.init();

}( jQuery, window.elementorFrontend ) );