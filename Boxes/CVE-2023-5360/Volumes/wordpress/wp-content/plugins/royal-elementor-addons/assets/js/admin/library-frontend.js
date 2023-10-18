( function( $ ) {

	"use strict";

	// Modal Popups
	var WprLibraryTmpls = {

		sectionIndex: null,
		contentID: 0,
		logoURL: white_label.logo_url,

		init: function() {
			window.elementor.on( 'preview:loaded', WprLibraryTmpls.previewLoaded );
		},

		previewLoaded: function() {
			var previewIframe = window.elementor.$previewContents,
				addNewSection = previewIframe.find( '.elementor-add-new-section' ),
				libraryButton = '<div id="wpr-library-btn" class="elementor-add-section-area-button" style="background:url('+ WprLibraryTmpls.logoURL +') no-repeat center center / contain;"></div>';

			// Add Library Button
            var elementorAddSection = $("#tmpl-elementor-add-section"),
            	elementorAddSectionText = elementorAddSection.text();
            elementorAddSectionText = elementorAddSectionText.replace('<div class="elementor-add-section-drag-title', libraryButton +'<div class="elementor-add-section-drag-title');
            elementorAddSection.text(elementorAddSectionText);

			$( previewIframe ).on( 'click', '.elementor-editor-section-settings .elementor-editor-element-add', function() {
				var addNewSectionWrap = $(this).closest( '.elementor-top-section' ).prev( '.elementor-add-section' ),
					modelID = $(this).closest( '.elementor-top-section' ).data( 'model-cid' );

				// Add Library Button
				if ( 0 == addNewSectionWrap.find( '#wpr-library-btn' ).length ) {
					setTimeout( function() {
						addNewSectionWrap.find( '.elementor-add-new-section' ).prepend( libraryButton );
					}, 110 );
				}

				// Set Section Index
				if ( window.elementor.elements.models.length ) {
					$.each( window.elementor.elements.models, function( index, model ) {
						if ( modelID === model.cid ) {
							WprLibraryTmpls.sectionIndex = index;
						}
					});
				}

				WprLibraryTmpls.contentID++;
			});

			// Open Popup Predefined Styles
			if ( 'wpr-popups' === window.elementor.config.document.type ) {
				setTimeout(function() {
					if ( 0 === previewIframe.find('.elementor-section-wrap.ui-sortable').children().length ) {
						previewIframe.find('#wpr-library-btn').trigger('click');
					}
				}, 2000);
			}

			// Popup
			previewIframe.on( 'click', '#wpr-library-btn', function() {

				// Render Popup
				WprLibraryTmpls.renderPopup( previewIframe );

				// Render Content
				if ( 'wpr-popups' === window.elementor.config.document.type && undefined === previewIframe.find('#wpr-library-btn').attr('data-filter') ) {
					WprLibraryTmpls.renderPopupContent( previewIframe, 'popups' );
				} else {
					WprLibraryTmpls.renderPopupContent( previewIframe, 'blocks' );
				}

				// Filter Content
				$(document).on( 'wpr-filter-popup-content', function() {
					var filter = previewIframe.find('#wpr-library-btn').attr('data-filter');
					previewIframe.find( '.wpr-tplib-sidebar ul li[data-filter="'+ filter +'"]' ).trigger('click');
				});

			});
		},

		renderPopup: function( previewIframe ) {
			// Render
			if ( previewIframe.find( '.wpr-tplib-popup' ).length == 0 ) {
				var headerNavigation = '<li data-tab="blocks" class="wpr-tplib-active-tab">Blocks</li>';

				if ( 'wpr-popups' === window.elementor.config.document.type ) {
					if ( undefined === previewIframe.find('#wpr-library-btn').attr('data-filter') ) {
						var headerNavigation = '\
							<li data-tab="blocks">Blocks</li>\
							<li data-tab="popups" class="wpr-tplib-active-tab">Popups</li>';
					} else {
						var headerNavigation = '\
							<li data-tab="blocks" class="wpr-tplib-active-tab">Blocks</li>\
							<li data-tab="popups">Popups</li>';
					}
				}

				previewIframe.find( 'body' ).append( '\
					<div class="wpr-tplib-popup-overlay">\
						<div class="wpr-tplib-popup">\
							<div class="wpr-tplib-header elementor-clearfix">\
								<div class="wpr-tplib-logo"><span class="wpr-library-icon" style="background:url('+ WprLibraryTmpls.logoURL +') no-repeat center center / contain;">RE</span>Library</div>\
								<div class="wpr-tplib-back" data-tab="">\
									<i class="eicon-chevron-left"></i> <span>Back to Library</span>\
								</div>\
								<ul>'+ headerNavigation +'</ul>\
								<span class="wpr-tplib-insert-template"><i class="eicon-file-download"></i> <span>Insert</span></span>\
								<div class="wpr-tplib-close"><i class="eicon-close"></i></div>\
							</div>\
							<div class="wpr-tplib-content-wrap elementor-clearfix">\
							</div>\
							<div class="wpr-tplib-preview-wrap"></div>\
						</div>\
					</div>\
				' );
			}
			
			// Show Overlay
			$e.run( 'panel/close' );
			$('#wpr-template-settings-notification').hide();
			previewIframe.find('html').css('overflow','hidden');
			previewIframe.find('.wpr-tplib-preview-wrap iframe').remove();
			previewIframe.find( '.wpr-tplib-popup-overlay' ).show();

			// Close
			previewIframe.find( '.wpr-tplib-close' ).on( 'click', function() {
				$e.run( 'panel/open' );
				$('#wpr-template-settings-notification').show();
				previewIframe.find('html').css('overflow','auto');
				
				previewIframe.find( '.wpr-tplib-popup-overlay' ).fadeOut( 'fast', function() {
					previewIframe.find('.wpr-tplib-popup-overlay').remove();
					previewIframe.find('#wpr-library-btn').removeAttr('data-filter');
				});
			});

			// Render Content
			previewIframe.find('.wpr-tplib-header').find('li').on( 'click', function() {
				previewIframe.find( '.wpr-tplib-header' ).find('li').removeClass( 'wpr-tplib-active-tab' );
				$(this).addClass( 'wpr-tplib-active-tab' );

				// Render Tab Content
				WprLibraryTmpls.renderPopupContent( previewIframe, $(this).data('tab') );
			});

			// Close Preview
			previewIframe.find( '.wpr-tplib-back' ).on( 'click', function() {
				$(this).hide();
				previewIframe.find('.wpr-tplib-close i').css('border-left', '0');
				previewIframe.find('.wpr-tplib-header').find('.wpr-tplib-insert-template').hide();
				previewIframe.find('.wpr-tplib-header').find('.wpr-tplib-insert-template').removeClass('wpr-tplib-insert-pro');
				previewIframe.find( '.wpr-tplib-preview-wrap' ).hide();
				previewIframe.find( '.wpr-tplib-preview-wrap' ).html('');
				previewIframe.find('.wpr-tplib-popup').css('overflow','hidden');

				previewIframe.find( '.wpr-tplib-logo' ).show();
				previewIframe.find( '.wpr-tplib-header ul li' ).show();
				previewIframe.find( '.wpr-tplib-content-wrap' ).show();
			});	
		},

		renderPopupContent: function( previewIframe, tab ) {
			WprLibraryTmpls.renderPopupLoader( previewIframe );
			
			// AJAX Data
			var data = {
				action: 'render_library_templates_'+ tab,
			};

			// Update via AJAX
			$.post(ajaxurl, data, function( response ) {
				previewIframe.find( '.wpr-tplib-content-wrap' ).html( response );

			// Render Preview
			}).always(function() {
				// Template Preview
				previewIframe.find( '.wpr-tplib-template-media' ).on( 'click', function() {
					var module = $(this).parent().data('filter'),
						template = $(this).parent().data('slug'),
						previewUrl = 'https://royal-elementor-addons.com/premade-styles/'+ $(this).parent().data('preview-url'),
						previewType = $(this).parent().data('preview-type'),
						proRefferal = '';

					if ( $(this).closest('.wpr-tplib-pro-wrap').length ) {
						proRefferal = '-pro';
						previewIframe.find('.wpr-tplib-header').find('.wpr-tplib-insert-template').addClass('wpr-tplib-insert-pro');
					}

					previewIframe.find('.wpr-tplib-header').find('.wpr-tplib-insert-template').attr('data-filter', module).attr('data-slug', template);

					previewIframe.find('.wpr-tplib-header').find('.wpr-tplib-insert-template').html($(this).parent().find('.wpr-tplib-insert-template').html());

					previewIframe.find('.wpr-tplib-close i').css('border-left', '1px solid #e8e8e8');

					// Hide Extra
					previewIframe.find('.wpr-tplib-logo').hide();
					previewIframe.find('.wpr-tplib-header ul li').hide();
					previewIframe.find('.wpr-tplib-back').show();
					previewIframe.find('.wpr-tplib-header').find('.wpr-tplib-insert-template').show();
					
					// Load Iframe
					previewIframe.find('.wpr-tplib-content-wrap').hide();
					previewIframe.find('.wpr-tplib-preview-wrap').show();

					if ( 'iframe' == previewType ) {
						previewIframe.find('.wpr-tplib-preview-wrap').html( '<div class="wpr-tplib-iframe"><iframe src="'+ previewUrl +'?ref=rea-plugin-library-preview'+ proRefferal +'"></iframe></div>' );
						previewIframe.find('.wpr-tplib-popup').css('overflow','hidden');
					} else {
						previewUrl = previewUrl.replace('premade-styles', 'library/premade-styles');
						previewIframe.find('.wpr-tplib-preview-wrap').html( '<div class="wpr-tplib-image"><img src="'+ previewUrl +'"></div>' );

						// Enable Scroll for Image Previews
						previewIframe.find('.wpr-tplib-popup').css('overflow','scroll');
					}
				});

				// Filters
				previewIframe.find( '.wpr-tplib-filters-list ul li' ).on( 'click', function() {
					var current = $(this).attr( 'data-filter' );

					// Show/Hide
					if ( 'all' === current ) {
						previewIframe.find( '.wpr-tplib-template' ).parent().show();
					} else {
						previewIframe.find( '.wpr-tplib-template' ).parent().hide();
						previewIframe.find( '.wpr-tplib-template[data-filter="'+ current +'"]' ).parent().show();
					}

					previewIframe.find('.wpr-tplib-filters h3 span').attr('data-filter', current).text($(this).text());

					// Sub Filters - TODO: Enable When Pro is Integrated
					// if ( -1 !== current.indexOf('grid') ) {
					// 	previewIframe.find( '.wpr-tplib-sub-filters' ).show();
					// } else {
					// 	previewIframe.find( '.wpr-tplib-sub-filters' ).hide();
					// }

					WprLibraryTmpls.renderPopupGrid( previewIframe );
				});

				// Sub Filters
				previewIframe.find( '.wpr-tplib-sub-filters ul li' ).on( 'click', function() {
					var current = $(this).attr( 'data-sub-filter' ),
						parentFilter = previewIframe.find('.wpr-tplib-filters h3 span').attr('data-filter');

					// Active Class
					previewIframe.find( '.wpr-tplib-sub-filters ul li' ).removeClass( 'wpr-tplib-activ-filter' );
					$(this).addClass( 'wpr-tplib-activ-filter' );

					// Show/Hide
					if ( 'all' === current ) {
						previewIframe.find( '.wpr-tplib-template[data-filter="'+ parentFilter +'"]' ).parent().show();
					} else {
						previewIframe.find( '.wpr-tplib-template' ).parent().hide();
						previewIframe.find( '.wpr-tplib-template[data-filter="'+ parentFilter +'"][data-sub-filter="'+ current +'"]' ).parent().show();
					}

					WprLibraryTmpls.renderPopupGrid( previewIframe );
				});

				// Search
				var keywords = [];

				// previewIframe.find( '.wpr-tplib-template' ).each( function() {
				// 	var kw = $(this).attr( 'data-keywords' );
				// 	keywords[$(this).attr( 'data-slug' )] = kw.split( ', ' );
				// });

				previewIframe.find( '.wpr-tplib-sidebar input' ).on( 'keyup', function() {
					if ( '' !== $(this).val() ) {
						previewIframe.find( '.wpr-tplib-template' ).parent().hide();
						previewIframe.find( '.wpr-tplib-template[data-keywords*="'+ $(this).val() +'"]' ).parent().show();
					} else {
						previewIframe.find( '.wpr-tplib-template' ).parent().show();
					}
				});

				// Import Template
				previewIframe.find( '.wpr-tplib-insert-template' ).on( 'click', function() {
					var module = ( $(this).parent().hasClass('wpr-tplib-header') ) ? $(this).attr( 'data-filter' ) : $(this).closest( '.wpr-tplib-template' ).attr( 'data-filter' ),
						template = ( $(this).parent().hasClass('wpr-tplib-header') ) ? $(this).attr( 'data-slug' ) : $(this).closest( '.wpr-tplib-template' ).attr( 'data-slug' ),
						activeTab = previewIframe.find('.wpr-tplib-active-tab').attr('data-tab');

					// Popup Templates
					if ( 'wpr-popups' === window.elementor.config.document.type && 'popups' === activeTab ) {
						module = 'popups/'+ module;
					}

					// Purchase Page
					if ( $(this).hasClass('wpr-tplib-insert-pro') ) {
						var href = window.location.href,
							adminUrl = href.substring(0, href.indexOf('/wp-admin')+9);

						// window.open(adminUrl +'/admin.php?page=wpr-addons-pricing', '_blank');
						window.open('https://royal-elementor-addons.com/?ref=rea-plugin-library-'+ module +'-upgrade-pro#purchasepro', '_blank');
						return;
					}

					previewIframe.find('.wpr-tplib-content-wrap').show();
					previewIframe.find('.wpr-tplib-preview-wrap').hide();
					WprLibraryTmpls.renderPopupLoader( previewIframe );

					// AJAX Data
					var data = {
						action: 'wpr_import_library_template',
						slug: module +'/'+ template
					};

					// Update via AJAX
					$.post(ajaxurl, data, function(response) {
						var importFile = response.substring( 0, response.length-1 ),
							importFile = JSON.parse( importFile );

						importFile.content[0].id += WprLibraryTmpls.contentID;

						// Insert Template
						window.elementor.getPreviewView().addChildModel( importFile.content, { at: WprLibraryTmpls.sectionIndex } );

						if ( 'wpr-popups' === window.elementor.config.document.type && 'popups' === activeTab ) {
							var defaults = {
								popup_trigger: 'load',
								popup_show_again_delay: '0',
								popup_load_delay: '1',
								popup_display_as: 'modal',
								popup_width: {
									unit: 'px',
									size: '650'
								},
								popup_height: 'auto',
								popup_align_hr: 'center',
								popup_align_vr: 'center',
								popup_content_align: 'flex-start',
								popup_animation: 'fadeIn',
								popup_animation_duration: '1',
								popup_overlay_display: 'yes',
								popup_close_button_display: 'yes',
								popup_container_bg_background: 'classic',
								popup_container_bg_position: 'center center',
								popup_container_bg_repeat: 'no-repeat',
								popup_container_bg_size: 'cover',
								popup_overlay_bg_background: 'classic',
								popup_container_padding: {
									isLinked: true,
									unit: 'px',
									top: 20,
									right: 20,
									bottom: 20,
									left: 20,
								},
								popup_container_radius: {
									isLinked: true,
									unit: 'px',
									top: 0,
									right: 0,
									bottom: 0,
									left: 0,
								}
							};
							// Reset Defaults
							elementor.settings.page.model.set(defaults);

							// Import Page Settings
							elementor.settings.page.model.set( importFile.page_settings );
						}

						// Fix Update Button
						window.elementor.panel.$el.find('#elementor-panel-footer-saver-publish button').removeClass('elementor-disabled');
						window.elementor.panel.$el.find('#elementor-panel-footer-saver-options button').removeClass('elementor-disabled');

						// Reset Section Index
						WprLibraryTmpls.sectionIndex = null;

						// Close Library
						$e.run( 'panel/open' );
						$('#wpr-template-settings-notification').show();
						previewIframe.find('html').css('overflow','auto');
						previewIframe.find( '.wpr-tplib-popup-overlay' ).fadeOut( 'fast', function() {
							previewIframe.find('.wpr-tplib-popup-overlay').remove();
							previewIframe.find('#wpr-library-btn').removeAttr('data-filter');
						});
					});
				});

				$(document).trigger('wpr-filter-popup-content');

				previewIframe.find('.wpr-tplib-filters').on('click', function(){
					if ( '0' == previewIframe.find('.wpr-tplib-filters-list').css('opacity') ) {
						previewIframe.find('.wpr-tplib-filters-list').css({
							'opacity' : '1',
							'visibility' : 'visible'
						});
					} else {
						previewIframe.find('.wpr-tplib-filters-list').css({
							'opacity' : '0',
							'visibility' : 'hidden'
						});
					}
				});

				previewIframe.on('click', function(){
					if ( '1' == previewIframe.find('.wpr-tplib-filters-list').css('opacity') ) {
						previewIframe.find('.wpr-tplib-filters-list').css({
							'opacity' : '0',
							'visibility' : 'hidden'
						});
					}
				});

				WprLibraryTmpls.renderPopupGrid( previewIframe );

			}); // end always

		},

		renderPopupLoader: function( previewIframe ) {
			previewIframe.find( '.wpr-tplib-content-wrap' ).prepend('<div class="wpr-tplib-loader"><div class="elementor-loader-wrapper"><div class="elementor-loader"><div class="elementor-loader-boxes"><div class="elementor-loader-box"></div><div class="elementor-loader-box"></div><div class="elementor-loader-box"></div><div class="elementor-loader-box"></div></div></div><div class="elementor-loading-title">Loading</div></div></div>');
		},

		renderPopupGrid: function( previewIframe ) {

			// Run Macy
			var macy = Macy({
				container: previewIframe.find('.wpr-tplib-template-gird-inner')[0],
				waitForImages: true,
				margin: 30,
				columns: 5,
				breakAt: {
					1370: 4,
					940: 3,
					520: 2,
					400: 1
				}
			});

			setTimeout(function(){
				macy.recalculate(true);
			}, 300 );

			setTimeout(function(){
				macy.recalculate(true);
			}, 600 );

		}

	};

	$( window ).on( 'elementor:init', WprLibraryTmpls.init );

}( jQuery ) );