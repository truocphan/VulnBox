( function( $ ) {//TODO: manage comments

	"use strict";

	var mutationObserver = new MutationObserver(function(mutations) {
		// Elementor Search Input
		if ( $('#elementor-panel-elements-search-input').length ) {

			var searchTimeout = null;  
			$('#elementor-panel-elements-search-input').on( 'keyup', function(e) {
				if ( e.which === 13 ) {
					return false;
				}

				var val = $(this).val();

				if (searchTimeout != null) {
					clearTimeout(searchTimeout);
				}

				searchTimeout = setTimeout(function() {
					searchTimeout = null;

					elementorCommon.ajax.addRequest( 'wpr_elementor_search_data', {
						data: {
						    search_query: val,
						},
						success: function() {
							console.log('Success!');
						}
					});
				}, 1000);
			});

		}
	});

	// Listen to Elementor Panel Changes
	mutationObserver.observe($('#elementor-panel')[0], {
	  childList: true,
	  subtree: true,
	});


	// Make our custom css visible in the panel's front-end
	elementor.hooks.addFilter( 'editor/style/styleText', function( css, context ) {
		if ( ! context ) {
			return;
		}

		var model = context.model,
			customCSS = model.get('settings').get('wpr_custom_css');
		var selector = '.elementor-element.elementor-element-' + model.get('id');
		
		if ( 'document' === model.get('elType') ) {
			selector = elementor.config.document.settings.cssWrapperSelector;
		}

		if ( customCSS ) {
			css += customCSS.replace(/selector/g, selector);
		}

		return css;
	});
	
	// Shortcode Widget: Select Template
	function selectShortcodeTemplate( model, e, textarea ) {
			var data = e.params.data;

			// Update Settings
			model.attributes.settings.attributes.shortcode = '[wpr-template id="'+ data.id +'"]';

			// Update Textarea
			textarea.val('[wpr-template id="'+ data.id +'"]');

			// Refresh Preview
			model.renderRemoteServer();
	}

	elementor.hooks.addAction( 'panel/open_editor/widget/shortcode', function( panel, model, view ) {

		var $select = panel.$el.find('.elementor-control-type-select2').find('select'),
			$textarea = panel.$el.find('.elementor-control-type-textarea').find('textarea');

		// Change
		$select.on( 'select2:select', function( e ) {
			selectShortcodeTemplate( model, e, $textarea );
		});

		// Render
		panel.$el.find('#elementor-controls').on( 'DOMNodeInserted ', '.elementor-control-type-select2', function(){
			$(this).find( 'select' ).on( 'select2:select', function( e ) {
				selectShortcodeTemplate( model, e, $textarea );
			} );
		});
	} );


	// WPR Grid Widget: Select Element (Filter Taxonomies)
	function filterGridTaxonomies( data, value ) {
		var options = [];

		for ( var key in data ) {
			if ( key !== value ) {
				for ( var i = 0; i < data[key].length; i++ ) {
					options.push( '.elementor-control-element_select select option[value="'+ data[key][i] +'"]' );
				}
			}
		}

		// Reset
		$( 'head' ).find( '#element_select_filter_style' ).remove();

		if ( 'related' === value || 'current' === value ) {
			return;
		}

		// Append Styles
		$( 'head' ).append('<style id="element_select_filter_style">'+ options.join(',') +' { display: none !important; }</style>');	
	}

	// WPR Grid Widget: Post Meta Keys (Filter by Query)
	function filterGridMetaKeys( data, value ) {
		var options = [];

		for ( var key in data ) {
			if ( key !== value ) {
				for ( var i = 0; i < data[key].length; i++ ) {
					options.push( '.select2-results__options li[data-select2-id*="-'+ data[key][i] +'"]' );
				}
			}
		}

		// Reset
		$( 'head' ).find( '#post_meta_keys_filter_style' ).remove();

		// Append Styles
		$( 'head' ).append('<style id="post_meta_keys_filter_style">'+ options.join(',') +' { display: none !important; }</style>');	
	}

	// WPR Grid Widget / List style: Element Location
	function disableListLocation( value ) {
		// Reset
		$( 'head' ).find( '#list_element_location_style' ).remove();

		if ( 'list' !== value ) {
			return;
		}

		// Append Styles
		$( 'head' ).append('<style id="list_element_location_style">.elementor-control-element_location option[value="above"] { display: none !important; }</style>');	
	}

	// Grid
	elementor.hooks.addAction( 'panel/open_editor/widget/wpr-grid', function( panel, model, view ) {
		var $querySource = panel.$el.find('.elementor-control-query_source').find( 'select' ),
			taxonomies = JSON.parse( panel.$el.find('.elementor-control-element_select_filter').find('input').val() ),
			metaKeys = JSON.parse( panel.$el.find('.elementor-control-post_meta_keys_filter').find('input').val() );

		// Open
		filterGridTaxonomies( taxonomies, $querySource.val() );
		filterGridMetaKeys( metaKeys, $querySource.val() );

		// Change
		$querySource.on( 'change', function() {
			filterGridTaxonomies( taxonomies, $(this).val() );
			filterGridMetaKeys( metaKeys, $(this).val() );
		});

		// Render Query Source
		panel.$el.find('#elementor-controls').on( 'DOMNodeInserted ', '.elementor-control-query_source', function(){
			$(this).find( 'select' ).on( 'change', function() {
				filterGridTaxonomies( taxonomies, $(this).val() );
				filterGridMetaKeys( metaKeys, $(this).val() );
			} );
		});

		// Render Layout Select
		panel.$el.find('#elementor-controls').on( 'DOMNodeInserted ', '.elementor-control-layout_select', function(){
			disableListLocation( $(this).find( 'select' ).val() );

			$(this).find( 'select' ).on( 'change', function() {
				disableListLocation( $(this).val() );
			} );
		});

		// Render Grid Elements
		panel.$el.find('#elementor-controls').on( 'DOMNodeInserted ', '.elementor-control-grid_elements', function() {

			$(this).find( '.elementor-control-element_select select' ).on( 'change', function() {
				var wrapper = $(this).closest( '.elementor-repeater-row-controls' );

				if ( 'lightbox' === $(this).val() ) {
					wrapper.find('.elementor-control-element_location').find( 'select' ).val( 'over' ).trigger( 'change' );
					wrapper.find('.elementor-control-element_animation').find( 'select' ).val( 'fade-in' ).trigger( 'change' );
					wrapper.find('.elementor-control-element_align_hr').find( 'input' ).eq(1).prop('checked',true).trigger( 'change' );
					wrapper.find('.elementor-control-element_lightbox_overlay').find( 'input' ).prop('checked',true).trigger( 'change' );
					wrapper.find('.elementor-control-element_extra_icon_pos').find( 'select' ).val( 'before' ).trigger( 'change' );
					setTimeout(function() {
						wrapper.find('.elementor-control-element_extra_icon_pos').addClass( 'elementor-hidden-control' );
					}, 100 );
				} else {
					wrapper.find('.elementor-control-element_extra_text_pos').find( 'select' ).val( 'none' ).trigger( 'change' );
					wrapper.find('.elementor-control-element_extra_icon_pos').find( 'select' ).val( 'none' ).trigger( 'change' );
					wrapper.find('.elementor-control-element_extra_icon_pos').removeClass( 'elementor-hidden-control' );
				}
			} );
		});

		var sOffsets = {};

		// Prevent Bubble on Click
		panel.$el.find('#elementor-controls').on( 'DOMNodeInserted ', '.elementor-control-type-section', function() {
			var current = $(this),
				attrClass = current.attr( 'class' ),
				firstIndex = attrClass.indexOf( 'elementor-control-section_' ),
				lastIndex = attrClass.indexOf( 'elementor-control-type-section' ) - 1;

			var oKey = attrClass.substring( firstIndex, lastIndex ),
				oProperty = current.offset().top;

			sOffsets[oKey] = oProperty;

			setTimeout(function() {
				current.on( 'click', function( event ) {
					var current = $(this),
						attrClass = current.attr( 'class' ),
						firstIndex = attrClass.indexOf( 'elementor-control-section_' ),
						lastIndex = attrClass.indexOf( 'elementor-control-type-section' ) - 1,
						sectionClass = attrClass.substring( firstIndex, lastIndex );

					setTimeout( function() {
						$( '#elementor-panel-content-wrapper' ).scrollTop( sOffsets[sectionClass] - 100 );
					}, 10 );
				});
			}, 100 );
		});
	} );

	// Image Grid
	elementor.hooks.addAction( 'panel/open_editor/widget/wpr-media-grid', function( panel, model, view ) {
		// Render Grid Elements
		panel.$el.find('#elementor-controls').on( 'DOMNodeInserted ', '.elementor-control-grid_elements', function() {
			$(this).find( '.elementor-control-element_select select' ).on( 'change', function() {
				var wrapper = $(this).closest( '.elementor-repeater-row-controls' );

				if ( 'lightbox' === $(this).val() ) {
					wrapper.find('.elementor-control-element_location').find( 'select' ).val( 'over' ).trigger( 'change' );
					wrapper.find('.elementor-control-element_animation').find( 'select' ).val( 'fade-in' ).trigger( 'change' );
					wrapper.find('.elementor-control-element_align_hr').find( 'input' ).eq(1).prop('checked',true).trigger( 'change' );
					wrapper.find('.elementor-control-element_lightbox_overlay').find( 'input' ).prop('checked',true).trigger( 'change' );
					wrapper.find('.elementor-control-element_extra_icon_pos').find( 'select' ).val( 'before' ).trigger( 'change' );
					setTimeout(function() {
						wrapper.find('.elementor-control-element_extra_icon_pos').addClass( 'elementor-hidden-control' );
					}, 100 );
				} else {
					wrapper.find('.elementor-control-element_extra_text_pos').find( 'select' ).val( 'none' ).trigger( 'change' );
					wrapper.find('.elementor-control-element_extra_icon_pos').find( 'select' ).val( 'none' ).trigger( 'change' );
					wrapper.find('.elementor-control-element_extra_icon_pos').removeClass( 'elementor-hidden-control' );
				}
			} );
		});
	} );

	// Woo Grid
	elementor.hooks.addAction( 'panel/open_editor/widget/wpr-woo-grid', function( panel, model, view ) {
		// Render Grid Elements
		panel.$el.find('#elementor-controls').on( 'DOMNodeInserted ', '.elementor-control-grid_elements', function() {
			$(this).find( '.elementor-control-element_select select' ).on( 'change', function() {
				var wrapper = $(this).closest( '.elementor-repeater-row-controls' );

				if ( 'lightbox' === $(this).val() ) {
					wrapper.find('.elementor-control-element_location').find( 'select' ).val( 'over' ).trigger( 'change' );
					wrapper.find('.elementor-control-element_animation').find( 'select' ).val( 'fade-in' ).trigger( 'change' );
					wrapper.find('.elementor-control-element_align_hr').find( 'input' ).eq(1).prop('checked',true).trigger( 'change' );
					wrapper.find('.elementor-control-element_lightbox_overlay').find( 'input' ).prop('checked',true).trigger( 'change' );
					wrapper.find('.elementor-control-element_extra_icon_pos').find( 'select' ).val( 'before' ).trigger( 'change' );
					setTimeout(function() {
						wrapper.find('.elementor-control-element_extra_icon_pos').addClass( 'elementor-hidden-control' );
					}, 100 );
				} else {
					wrapper.find('.elementor-control-element_extra_text_pos').find( 'select' ).val( 'none' ).trigger( 'change' );
					wrapper.find('.elementor-control-element_extra_icon_pos').find( 'select' ).val( 'none' ).trigger( 'change' );
					wrapper.find('.elementor-control-element_extra_icon_pos').removeClass( 'elementor-hidden-control' );
				}
			} );
		});	

		var sOffsets = {};

		// Prevent Bubble on Click - not working - //tmp
		panel.$el.find('#elementor-controls').on( 'DOMNodeInserted ', '.elementor-control-type-section', function() {
			var current = $(this),
				attrClass = current.attr( 'class' ),
				firstIndex = attrClass.indexOf( 'elementor-control-section_' ),
				lastIndex = attrClass.indexOf( 'elementor-control-type-section' ) - 1;

			var oKey = attrClass.substring( firstIndex, lastIndex ),
				oPropery = current.offset().top;

			sOffsets[oKey] = oPropery;

			setTimeout(function() {
				current.on( 'click', function( event ) {
					var current = $(this),
						attrClass = current.attr( 'class' ),
						firstIndex = attrClass.indexOf( 'elementor-control-section_' ),
						lastIndex = attrClass.indexOf( 'elementor-control-type-section' ) - 1,
						sectionClass = attrClass.substring( firstIndex, lastIndex );

					setTimeout( function() {
						$( '#elementor-panel-content-wrapper' ).scrollTop( sOffsets[sectionClass] - 100 );
					}, 10 );
				});
			}, 100 );
		});
	} );

	// Instagram Feed
	elementor.hooks.addAction( 'panel/open_editor/widget/wpr-instagram-feed', function( panel, model, view ) {
		// Render Grid Elements
		panel.$el.find('#elementor-controls').on( 'DOMNodeInserted ', '.elementor-control-insta_feed_elements', function() {
			$(this).find( '.elementor-control-element_select select' ).on( 'change', function() {
				var wrapper = $(this).closest( '.elementor-repeater-row-controls' );

				if ( 'lightbox' === $(this).val() ) {
					wrapper.find('.elementor-control-element_location').find( 'select' ).val( 'over' ).trigger( 'change' );
					wrapper.find('.elementor-control-element_animation').find( 'select' ).val( 'fade-in' ).trigger( 'change' );
					wrapper.find('.elementor-control-element_align_hr').find( 'input' ).eq(1).prop('checked',true).trigger( 'change' );
					wrapper.find('.elementor-control-element_lightbox_overlay').find( 'input' ).prop('checked',true).trigger( 'change' );
					wrapper.find('.elementor-control-element_extra_icon_pos').find( 'select' ).val( 'before' ).trigger( 'change' );
					setTimeout(function() {
						wrapper.find('.elementor-control-element_extra_icon_pos').addClass( 'elementor-hidden-control' );
					}, 100 );
				} else {
					wrapper.find('.elementor-control-element_extra_text_pos').find( 'select' ).val( 'none' ).trigger( 'change' );
					wrapper.find('.elementor-control-element_extra_icon_pos').find( 'select' ).val( 'none' ).trigger( 'change' );
					wrapper.find('.elementor-control-element_extra_icon_pos').removeClass( 'elementor-hidden-control' );
				}
			} );
		});
	} );

	// Get Referrer Link
	var referrer = document.referrer;

	// Return to Plugin Page
	if ( '' !== referrer && referrer.indexOf( 'page=wpr-addons' ) > -1 ) {
		$(window).on( 'load', function() {

			$('#elementor-panel-header-menu-button').on( 'click', function() {

				setTimeout(function() {
					$('.elementor-panel-menu-item-exit-to-dashboard').on( 'click', function() {
						window.location.href = referrer;
					});
				}, 300);
			});
		});
	}

	// Advanced Slider - TODO: Check if necessary or remove
	// elementor.hooks.addAction( 'panel/open_editor/widget/wpr-advanced-slider', function( panel, model, view ) {
	// 	var elControls = panel.$el,
	// 		$select = elControls.find('.elementor-control-slider_content_type').find('select');

	// 	if ( 'custom' !== $select.val() ) {
	// 		elControls.find('.elementor-control-slider_items .elementor-repeater-row-controls .elementor-control').addClass('wpr-elementor-hidden-control');
	// 		elControls.find('.elementor-control-slider_content_type').removeClass('wpr-elementor-hidden-control');
	// 		elControls.find('.elementor-control-slider_select_template').removeClass('wpr-elementor-hidden-control');
	// 	} else {
	// 		elControls.find('.elementor-control-slider_items .elementor-repeater-row-controls .elementor-control').removeClass('wpr-elementor-hidden-control');
	// 		elControls.find('.elementor-control-slider_select_template').addClass('wpr-elementor-hidden-control');
	// 	}

	// 	$select.on( 'change', function() {

	// 		if ( 'custom' !== $(this).val() ) {
	// 			elControls.find('.elementor-control-slider_items .elementor-repeater-row-controls .elementor-control').addClass('wpr-elementor-hidden-control');
	// 			elControls.find('.elementor-control-slider_content_type').removeClass('wpr-elementor-hidden-control');
	// 			elControls.find('.elementor-control-slider_select_template').removeClass('wpr-elementor-hidden-control');
	// 		} else {
	// 			elControls.find('.elementor-control-slider_items .elementor-repeater-row-controls .elementor-control').removeClass('wpr-elementor-hidden-control');
	// 			elControls.find('.elementor-control-slider_select_template').addClass('wpr-elementor-hidden-control');
	// 		}			
	// 	});
	// } );

	/*--------------------------------------------------------------
	== Widget Preview and Library buttons
	--------------------------------------------------------------*/

	for (const [key, value] of Object.entries(registered_modules)) {
		elementor.hooks.addAction( 'panel/open_editor/widget/wpr-'+ value[0], function( panel, model, view ) {
			openPedefinedStyles( panel.$el, view.$el, value[0], value[1], value[2] );
		} );
	}

	function openPedefinedStyles( panel, preview, widget, url, filter ) {
		panel.on( 'click', '.elementor-control-wpr_library_buttons .elementor-control-raw-html div a:first-child', function() {
			var theme = $(this).data('theme');
			$(this).attr('href', url +'?ref=rea-plugin-panel-'+ widget +'-utmtr'+ theme.slice(0,3) +'nkbs'+ theme.slice(3,theme.length) +'-preview'+ filter);
		});

		panel.on( 'click', '.elementor-control-wpr_library_buttons .elementor-control-raw-html div a:last-child', function() {
			preview.closest('body').find('#wpr-library-btn').attr('data-filter', widget);
			preview.closest('body').find('#wpr-library-btn').trigger('click');
		});
	}

}( jQuery ) );