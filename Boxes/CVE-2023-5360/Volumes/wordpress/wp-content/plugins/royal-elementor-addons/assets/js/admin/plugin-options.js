jQuery(document).ready(function( $ ) {
	"use strict";

	// Condition Selects
	var globalS  = '.global-condition-select',
		archiveS = '.archives-condition-select',
		singleS  = '.singles-condition-select',
		inputIDs = '.wpr-condition-input-ids';

	// Condition Popup
	var conditionPupup = $( '.wpr-condition-popup-wrap' );

	// Current Tab
	var currentTab = $('.nav-tab-active').attr( 'data-title' );
		if ( currentTab ) {
			currentTab = currentTab.trim().toLowerCase(),
			currentTab = currentTab.replace(' ', '_');
		}

	/*
	** Get Active Filter -------------------------
	*/
	function getActiveFilter() {
		var type = currentTab.replace( /\W+/g, '-' ).toLowerCase();
		if ( $('.template-filters').length > 0 ) {
			type = $('.template-filters .active-filter').last().attr('data-class');
			type = type.substring( 0, type.length - 1);
		}
		return type;
	}

	/*
	** Render User Template -------------------------
	*/
	function renderUserTemplate( type, title, slug, id ) {
		var html = '';

		html += '<li>';
			html += '<h3 class="wpr-title">'+ title +'</h3>';
			html += '<div class="wpr-action-buttons">';
				html += '<span class="wpr-template-conditions button-primary" data-slug="'+ slug +'">Manage Conditions</span>';
				html += '<a href="post.php?post='+ id +'&action=elementor" class="wpr-edit-template button-primary">Edit Template</a>';
				html += '<span class="wpr-delete-template button-primary" data-slug="'+ slug +'" data-warning="Are you sure you want to delete this template?"><span class="dashicons dashicons-no-alt"></span></span>';
			html += '</div>';
		html += '</li>';

		// Render
		$( '.wpr-my-templates-list.wpr-'+ getActiveFilter() +'-templates-list' ).prepend( html );

		if ( $('.wpr-empty-templates-message').length ) {
			$('.wpr-empty-templates-message').remove();
		}

		// Run Functions
		changeTemplateConditions();
		deleteTemplate();
	}

	/*
	** Create User Template -------------------------
	*/
	function createUserTemplate() {
		// Get Template Library
		var library = 'my_templates' === getActiveFilter() ? 'elementor_library' : 'wpr_templates';
		// Get Template Title
		var title = $('.wpr-user-template-title').val();
		
		// Get Template Slug
		var slug = 'user-'+ getActiveFilter() +'-'+ title.replace( /\W+/g, '-' ).toLowerCase();

		if ( 'elementor_library' === library ) {
			slug = getActiveFilter() +'-'+ title.replace( /\W+/g, '-' ).toLowerCase();
		}

		// AJAX Data
		var data = {
			action: 'wpr_create_template',
			nonce: WprPluginOptions.nonce,
			user_template_library: library,
			user_template_title: title,
			user_template_slug: slug,
			user_template_type: getActiveFilter(),
		};

		// Create Template
		$.post(ajaxurl, data, function(response) {
			// Close Popup
			$('.wpr-user-template-popup-wrap').fadeOut();

			// Open Conditions
			setTimeout(function() {
				// Get Template ID
				var id = response.substring( 0, response.length - 1 );

				// Redirect User to Editor
				if ( 'my_templates' === currentTab.replace( /\W+/g, '-' ).toLowerCase() ) {
					window.location.href = 'post.php?post='+ id +'&action=elementor';
					return;
				}

				// Set Template Slug & ID
				$( '.wpr-save-conditions' ).attr( 'data-slug', slug ).attr( 'data-id', id );

				// Render Template
				renderUserTemplate( getActiveFilter(), $('.wpr-user-template-title').val(), slug, id );

				if ( $('.wpr-no-templates').length ) {
					$('.wpr-no-templates').hide();
				}

				// Open Popup
				openConditionsPopup( slug );
				conditionPupup.addClass( 'editor-redirect' );
			}, 500);
		});
	}

	// Open Popup
	$('.wpr-user-template').on( 'click', function() {
		if ( $(this).find('div').length ) {
			alert('Please Install/Activate WooCommerce!');
			return;
		}

		$('.wpr-user-template-title').val('');
		$('.wpr-user-template-popup-wrap').fadeIn();
	});

	// Close Popup
	$('.wpr-user-template-popup').find('.close-popup').on( 'click', function() {
		$('.wpr-user-template-popup-wrap').fadeOut();
	});

	// Create - Click
	$('.wpr-create-template').on( 'click', function() {
		if ( '' === $('.wpr-user-template-title').val() ) {
			$('.wpr-user-template-title').css('border-color', 'red');
			if ( $('.wpr-fill-out-the-title').length < 1 ) {
				$('.wpr-create-template').before('<p class="wpr-fill-out-the-title"><em>Please fill the Title field.</em></p>');
				$('.wpr-fill-out-the-title').css('margin-top', '4px');
				$('.wpr-fill-out-the-title em').css({'color': '#7f8b96', 'font-size': 'smaller'});
			}
		} else {
			$('.wpr-user-template-title').removeAttr('style');
			$('.wpr-create-template + p').remove();

			// Create Template
			createUserTemplate();
		}
	});

	// Create - Enter Key
	$('.wpr-user-template-title').keypress(function(e) {
		if ( e.which == 13 ) {
			e.preventDefault();
			createUserTemplate();
		}
	});


	/*
	** Reset Template -------------------------
	*/
	function deleteTemplate() {
		$( '.wpr-delete-template' ).on( 'click', function() {

			// Buttons
			var deleteButton = $(this);

			if ( ! confirm(deleteButton.data('warning')) ) {
				return;
			}

			// Get Template Library
			var library = 'my_templates' === getActiveFilter() ? 'elementor_library' : 'wpr_templates';

			// Get Template Slug
			var slug = deleteButton.attr('data-slug');

			var oneTimeNonce = deleteButton.attr('data-nonce');

			// AJAX Data
			var data = {
				nonce: oneTimeNonce,
				action: 'wpr_delete_template',
				template_slug: slug,
				template_library: library,
			};

			// Remove Template via AJAX
			$.post(ajaxurl, data, function(response) {
				deleteButton.closest('li').remove();
			});

			// Save Conditions
			$.post(ajaxurl, data, function(response) {
				setTimeout(function(){
					if ( $('.wpr-my-templates-list li').length === 0 ) {
						$('.wpr-my-templates-list').append('<li class="wpr-no-templates">You don\'t have any templates yet!</li>');
						// $('.wpr-no-templates').hide();
					}
				}, 500);
			});

			// Delete associated Conditions
			if ( 'my_templates' !== getActiveFilter() ) {
				var conditions = JSON.parse($( '#wpr_'+ currentTab +'_conditions' ).val());
					delete conditions[slug];

				// Set Conditions
				$('#wpr_'+ currentTab +'_conditions').val( JSON.stringify(conditions) );

				// AJAX Data
				var data = {
					action: 'wpr_save_template_conditions',
					nonce: WprPluginOptions.nonce,
				};
				data['wpr_'+ currentTab +'_conditions'] = JSON.stringify(conditions);
			}
		});
	}

	deleteTemplate();

	/*
	** Condition Popup -------------------------
	*/
	// Open Popup
	function changeTemplateConditions() {
		$( '.wpr-template-conditions' ).on( 'click', function() {
			var template = $(this).attr('data-slug');

			// Set Template Slug
			$( '.wpr-save-conditions' ).attr( 'data-slug', template );

			// Open Popup
			openConditionsPopup( template );
		});		
	}

	changeTemplateConditions();

	// Close Popup
	conditionPupup.find('.close-popup').on( 'click', function() {
		conditionPupup.fadeOut();
	});


	/*
	** Popup: Clone Conditions -------------------------
	*/
	function popupCloneConditions() {
		// Clone
		$('.wpr-conditions-wrap').append( '<div class="wpr-conditions">'+ $('.wpr-conditions-sample').html() +'</div>' );

		// Add Tab Class
		// why removing and adding again ?
		$('.wpr-conditions').removeClass( 'wpr-tab-'+ currentTab ).addClass( 'wpr-tab-'+ currentTab );
		var clone = $('.wpr-conditions').last();

		// Reset Extra
		clone.find('select').not(':first-child').hide();

		// Entrance Animation
		clone.hide().fadeIn();

		// Hide Extra Options
		var currentFilter = $('.template-filters .active-filter').attr('data-class');

		if ( 'blog-posts' === currentFilter || 'custom-posts' === currentFilter ) {
			clone.find('.singles-condition-select').children(':nth-child(1),:nth-child(2),:nth-child(3)').remove();
			clone.find('.wpr-condition-input-ids').val('all').show();
		} else if ( 'woocommerce-products' === currentFilter ) {
			clone.find('.singles-condition-select').children().filter(function() {
				return 'product' !== $(this).val()
			}).remove();
			clone.find('.wpr-condition-input-ids').val('all').show();
		} else if ( '404-pages' === currentFilter ) {
			clone.find('.singles-condition-select').children().filter(function() {
				return 'page_404' !== $(this).val()
			}).remove();
		} else if ( 'blog-archives' === currentFilter || 'custom-archives' === currentFilter ) {
			clone.find('.archives-condition-select').children().filter(function() {
				return 'products' == $(this).val() || 'product_cat' == $(this).val() || 'product_tag' == $(this).val();
			}).remove();
		} else if ( 'woocommerce-archives' === currentFilter ) {
			clone.find('.archives-condition-select').children().filter(function() {
				return 'products' !== $(this).val() && 'product_cat' !== $(this).val() && 'product_tag' !== $(this).val();
			}).remove();
		}
	}

	/*
	** Popup: Add Conditions -------------------------
	*/
	function popupAddConditions() {
		$( '.wpr-add-conditions' ).on( 'click', function() {
			// Clone
			popupCloneConditions();

			// Reset
			$('.wpr-conditions').last().find('input').hide();//tmp -maybe remove

			// Show on Canvas
			if ( 'header' === currentTab || 'footer' === currentTab ) {
				$('.wpr-canvas-condition').show();
			}

			// Run Functions
			popupDeleteConditions();
			popupMainConditionSelect();
			popupSubConditionSelect();
		});
	}

	popupAddConditions();

	/*
	** Popup: Set Conditions -------------------------
	*/
	function popupSetConditions( template ) {
		var conditions = $( '#wpr_'+ currentTab +'_conditions' ).val();
			conditions = '' !== conditions ? JSON.parse(conditions) : {};
		// Reset
		$('.wpr-conditions').remove();

		// Setup Conditions
		if ( conditions[template] != undefined && conditions[template].length > 0 ) {
			// Clone
			for (var i = 0; i < conditions[template].length; i++) {
				popupCloneConditions();
				$( '.wpr-conditions' ).find('select').hide();
			}

			// Set
			if ( $('.wpr-conditions').length ) {
				$('.wpr-conditions').each( function( index ) {
					var path = conditions[template][index].split( '/' );

					for (var s = 0; s < path.length; s++) {
						if ( s === 0 ) {
							$(this).find(globalS).val(path[s]).trigger('change');
							$(this).find('.'+ path[s] +'s-condition-select').show();
						} else if ( s === 1 ) {
							path[s-1] = 'product_archive' === path[s-1] ? 'archive' : path[s-1];
							$(this).find('.'+ path[s-1] +'s-condition-select').val(path[s]).trigger('change');
						} else if ( s === 2 ) {
							$(this).find(inputIDs).val(path[s]).trigger('keyup').show();
						}
					}
				});
			}
		}

		// Set Show on Canvas Switcher value
		var conditionsBtn = $('.wpr-template-conditions[data-slug='+ template +']');

		if ( 'true' === conditionsBtn.attr('data-show-on-canvas') ) {
			$('.wpr-canvas-condition').find('input[type=checkbox]').attr('checked', 'checked');
		} else {
			$('.wpr-canvas-condition').find('input[type=checkbox]').removeAttr('checked');
		}
	}


	/*
	** Popup: Open -------------------------
	*/
	function openConditionsPopup( template ) {
		
		// Set Conditions
		popupSetConditions(template);
		popupMainConditionSelect();
		popupSubConditionSelect();
		showOnCanvasSwitcher();
		popupDeleteConditions();

		// Conditions Wrap
		var conditionsWrap = $( '.wpr-conditions' );

		// Reset Canvas Option
		$('.wpr-canvas-condition').hide();

		// Show Conditions
		if ( 'single' === currentTab || 'product_single' === currentTab ) {
			conditionsWrap.find(singleS).show();
		} else if ( 'archive' === currentTab || 'product_archive' === currentTab ) {
			conditionsWrap.find(archiveS).show();
		} else {
			conditionsWrap.find(globalS).show();

			// Show Canvas Option
			if ( $('.wpr-conditions').length ) {
				$('.wpr-canvas-condition').show();
			}
		}

		// Add Current Filter Class
		$('.wpr-conditions-wrap').addClass( $('.template-filters .active-filter').attr('data-class') );

		// Open Popup
		conditionPupup.fadeIn();
	}
	

	/*
	** Popup: Delete Conditions -------------------------------
	*/
	function popupDeleteConditions() {
		$( '.wpr-delete-template-conditions' ).on( 'click', function() {
			var current = $(this).parent(),
				conditions = $( '#wpr_'+ currentTab +'_conditions' ).val();
				conditions = '' !== conditions ? JSON.parse(conditions) : {};

			// Update Conditions
			$('#wpr_'+ currentTab +'_conditions').val( JSON.stringify( removeConditions( conditions, getConditionsPath(current) ) ) );

			// Remove Conditions
			current.fadeOut( 500, function() {
				$(this).remove();

				// Show on Canvas
				if ( 0 === $('.wpr-conditions').length ) {
					$('.wpr-canvas-condition').hide();
				}
			});

		});
	}


	/*
	** Popup: Condition Selection -------
	*/
	// General Condition Select
	function popupMainConditionSelect() {
		$(globalS).on( 'change', function() {
			var current = $(this).parent();

			// Reset
			current.find(archiveS).hide();
			current.find(singleS).hide();
			current.find(inputIDs).hide();

			// Show
			current.find( '.'+ $(this).val() +'s-condition-select' ).show();

		});
	}

	// Sub Condition Select
	function popupSubConditionSelect() {
		$('.archives-condition-select, .singles-condition-select').on( 'change', function() {
			var current = $(this).parent(),
				selected = $( 'option:selected', this ),
				value = $(this).val();

			// Show Custom ID input
			if ( selected.hasClass('custom-ids') || selected.hasClass('custom-type-ids') ) {
				current.find(inputIDs).val('all').trigger('keyup').show();
			} else {
				current.find(inputIDs).hide();
			}

			console.log(value);

			// Show/Hide Expert Notice
			if ( 0 === value.indexOf('pro-') ) {
				$('.wpr-expert-notice').show();
			} else {
				$('.wpr-expert-notice').hide();
			}
		});
	}

	// Show on Canvas Switcher
	function showOnCanvasSwitcher() {
		$('.wpr-canvas-condition input[type=checkbox]').on('change', function() {
			$('.wpr-template-conditions[data-slug='+ $('.wpr-save-conditions').attr('data-slug') +']').attr('data-show-on-canvas', $(this).prop('checked'));
		});
	}


	/*
	** Remove Conditions --------------------------
	*/
	function removeConditions( conditions, path ) {
		var data = [];

		// Get Templates
		$('.wpr-template-conditions').each(function() {
			data.push($(this).attr('data-slug'))
		});

		// Loop
		for ( var key in conditions ) {
			if ( conditions.hasOwnProperty(key) ) {
				// Remove Duplicate
				for (var i = 0; i < conditions[key].length; i++) {
					if ( path == conditions[key][i] ) {
						if ( 'popup' !== getActiveFilter() ) {
							conditions[key].splice(i, 1);
						}
					}
				};

				// Clear Database
				if ( data.indexOf(key) === -1 ) {
					delete conditions[key];
				}
			}
		}

		return conditions;
	}

	/*
	** Get Conditions Path -------------------------
	*/
	function getConditionsPath( current ) {
		var path = '';

		// Selects
		var global = 'none' !== current.find(globalS).css('display') ?  current.find(globalS).val() : currentTab,
			archive = current.find(archiveS).val(),
			single = current.find(singleS).val(),
			customIds = current.find(inputIDs);

		if ( 'archive' === global || 'product_archive' === global ) {
			if ( 'none' !== customIds.css('display') ) {
				path = global +'/'+ archive +'/'+ customIds.val();
			} else {
				path = global +'/'+ archive;
			}
		} else if ( 'single' === global || 'product_single' === global ) {
			if ( 'none' !== customIds.css('display') ) {
				path = global +'/'+ single +'/'+ customIds.val();
			} else {
				path = global +'/'+ single;
			}
		} else {
			path = 'global';
		}

		return path;
	}


	/*
	** Get Conditions -------------------------
	*/
	function getConditions( template, conditions ) {
		// Conditions
		conditions = ('' === conditions || '[]' === conditions) ? {} : JSON.parse(conditions);
		conditions[template] = [];

		$('.wpr-conditions').each( function() {
			var path = getConditionsPath( $(this) );

			// Remove Duplicates
			conditions = removeConditions( conditions, path );

			// Add New Values
			conditions[template].push( path );
		});

		return conditions;
	}


	/*
	** Save Conditions -------------------------
	*/
	function saveConditions() {
		$( '.wpr-save-conditions' ).on( 'click', function() {
			var proActive = (1 === $('.wpr-my-templates-list').data('pro')) ? true : false;

			// Current Template
			var template = $(this).attr('data-slug'),
				TemplateID = $(this).attr('data-id');

			// Get Conditions
			var conditions = getConditions( template, $( '#wpr_'+ currentTab +'_conditions' ).val() );

			// Don't save if not active
			if ( !proActive && ('header' === currentTab || 'footer' == currentTab || 'product_archive' == currentTab) ) {
				if ( 'header' === currentTab || 'footer' == currentTab ) {
					if ( ('global' !== conditions[template][0] && 'undefined' !== typeof conditions[template][0]) || conditions[template].length > 1 ) {
						alert('Please select "Entire Site" to continue! Mutiple and custom conditions are fully supported in the Pro version.');
						return;
					}
				} else if ( 'product_archive' == currentTab ) {
					console.log(conditions[template][0]);
					if ( ('product_archive/products' !== conditions[template][0] && 'undefined' !== typeof conditions[template][0]) || conditions[template].length > 1 ) {
						alert('Please select "Entire Site" to continue! Mutiple and custom conditions are fully supported in the Pro version.');
						return;
					}					
				}
			}

			// Set Conditions
			$('#wpr_'+ currentTab +'_conditions').val( JSON.stringify(conditions) );

			// AJAX Data
			var data = {
				action: 'wpr_save_template_conditions',
				nonce: WprPluginOptions.nonce,
				template: template
			};
			data['wpr_'+ currentTab +'_conditions'] = JSON.stringify(conditions);

			if ( $('#wpr-show-on-canvas').length ) {
				data['wpr_'+ currentTab +'_show_on_canvas'] = $('#wpr-show-on-canvas').prop('checked');
			}

			// Save Conditions
			$.post(ajaxurl, data, function(response) {
				// Close Popup
				conditionPupup.fadeOut();

				// Set Active Class
				for ( var key in conditions ) {
					if ( conditions[key] && 0 !== conditions[key].length ) {
						$('.wpr-delete-template[data-slug="'+ key +'"]').closest('li').addClass('wpr-active-conditions-template');
					} else {
						$('.wpr-delete-template[data-slug="'+ key +'"]').closest('li').removeClass('wpr-active-conditions-template');
					}
				}

				// Redirect User to Editor
				if ( conditionPupup.hasClass('editor-redirect') ) {
					window.location.href = 'post.php?post='+ TemplateID +'&action=elementor';
				}
			});
		});		
	}
	
	saveConditions();


	/*
	** Highlight Templates with Active Conditions --------
	*/
	if ( $('body').hasClass('royal-addons_page_wpr-theme-builder') || $('body').hasClass('royal-addons_page_wpr-popups') ) {
		if ( currentTab && 'my_templates' !== currentTab ) {
			var conditions = $( '#wpr_'+ currentTab +'_conditions' ).val(),
				conditions = ('' === conditions || '[]' === conditions) ? {} : JSON.parse(conditions);

			for ( var key in conditions ) {
				$('.wpr-delete-template[data-slug="'+ key +'"]').closest('li').addClass('wpr-active-conditions-template');
			}
		}
	}

	/*
	** Save Options with Ajax -------------------------
	*/
	$('.wpr-settings-page form').submit(function () {
		var settings =  $(this).serialize();

		$.post( 'options.php', settings ).error(function() {
			// alert('error');
		}).success(function() {
			$('.wpr-settings-saved').stop().fadeIn(500).delay(1000).fadeOut(1000); 
		});

		return false;    
	});

	$('.wpr-element').find('input').on( 'change', function() {
		$('.wpr-settings-page form').submit();
	});

	/*
	** Elements Toggle -------------------------
	*/
	$('.wpr-elements-toggle').find('input').on( 'change', function() {
		if ( $(this).is(':checked') ) {
			$('.wpr-element').find('input').prop( 'checked', true );
		} else {
			$('.wpr-element').find('input').prop( 'checked', false );
		}

		$('.wpr-settings-page form').submit();
	});


	/*
	** Elements Search --------------------------
	*/
	var searchTimeout = null;  
	$('.wpr-widgets-search').find('input').keyup(function(e) {
		if ( e.which === 13 ) {
			return false;
		}

		var val = $(this).val().toLowerCase();

		if (searchTimeout != null) {
			clearTimeout(searchTimeout);
		}

		searchTimeout = setTimeout(function() {
			searchTimeout = null;
			let visibleElements = 'none';
			
			// Reset
			$('.wpr-widgets-not-found').hide();
			$('.submit').show();

			if ( '' !== val ) {
				$('.wpr-elements, .wpr-element, .wpr-elements-heading').hide();
				$('.wpr-widgets-not-found').hide();
			} else {
				$('.wpr-elements, .wpr-element, .wpr-elements-heading').show();
				$('.wpr-elements-filters li').first().trigger('click');
			}

			$('.wpr-element').each(function(){
				let title = $(this).find('h3').text().toLowerCase();

				if ( -1 !== title.indexOf(val) ) {
					$(this).show();
					$(this).parent().show();
					visibleElements = 'visible';
				}
			});

			if ( 'none' === visibleElements ) {
				$('.wpr-widgets-not-found').css('display', 'flex');
				$('.submit').hide();
			}

			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {
					action: 'wpr_backend_widget_search_query_results',
					search_query: val
				},
				success: function( response ) {}
			});
		}, 1000);  
	});

	/*
	** Elements Filters -----------------------------------
	*/
	$('.wpr-elements-filters li').on('click', function() {
		let filter = $(this).data('filter');

		$('.wpr-elements-toggle').hide();
		$('.wpr-elements-filters li').removeClass('wpr-active-filter');
		$(this).addClass('wpr-active-filter');

		if ( 'all' === filter ) {
			$('.wpr-elements, .wpr-elements-heading').show();
			$('.wpr-elements-toggle').show();
		} else if ( 'theme' === filter ) {
			$('.wpr-elements, .wpr-elements-heading').hide();
			$('.wpr-elements-theme').show();
			$('.wpr-elements-theme').prev('.wpr-elements-heading').show();
		} else {
			$('.wpr-elements, .wpr-elements-heading').hide();
			$('.wpr-elements-woo').show();
			$('.wpr-elements-woo').prev('.wpr-elements-heading').show();
		}
	});

	/*
	** Settings Tab ------------------------------
	*/

	// Lightbox Settings
	jQuery(document).ready(function($){
		$('#wpr_lb_bg_color').wpColorPicker();
		$('#wpr_lb_toolbar_color').wpColorPicker();
		$('#wpr_lb_caption_color').wpColorPicker();
		$('#wpr_lb_gallery_color').wpColorPicker();
		$('#wpr_lb_pb_color').wpColorPicker();
		$('#wpr_lb_ui_color').wpColorPicker();
		$('#wpr_lb_ui_hr_color').wpColorPicker();
		$('#wpr_lb_text_color').wpColorPicker();

		// Fix Color Picker
		if ( $('.wpr-settings').length ) {
			$('.wpr-settings').find('.wp-color-result-text').text('Select Color');
			$('.wpr-settings').find('.wp-picker-clear').val('Clear');
		}
	});


	/*
	** Image Upload Option -----------------------
	*/
	$('body').on( 'click', '.wpr-setting-custom-img-upload button', function(e){
		e.preventDefault();

		var button = $(this);

		if ( ! button.find('img').length ) {
			var custom_uploader = wp.media({
				title: 'Insert image',
				library : {
					uploadedTo : wp.media.view.settings.post.id, // attach to the current post?
					type : 'image'
				},
				button: {
					text: 'Use this image' // button label text
				},
				multiple: false
			}).on('select', function() {
				var attachment = custom_uploader.state().get('selection').first().toJSON();

				button.find('i').remove();
				button.prepend('<img src="' + attachment.url + '">');
				button.find('span').text('Remove Image');

				$('#wpr_wl_plugin_logo').val(attachment.id);
			}).open();
		} else {
			button.find('img').remove();
			button.prepend('<i class="dashicons dashicons-cloud-upload"></i>');
			button.find('span').text('Upload Image');

			$('#wpr_wl_plugin_logo').val('');
		}
	
	});

    // $('a.wpr-authorize-instagram').on('click', function() {
    //     window.open('https://www.instagram.com/oauth/authorize?client_id=5484217328332432&redirect_uri=https://reastats.kinsta.cloud/token/social-network.php&scope=user_profile,user_media&response_type=code','popup','width=600,height=600'); 
    //     return false;
    // });


	//TODO: Remove this - only for development
	// $('.nav-tab-wrapper').after( '<p>'+ $('.nav-tab-wrapper').next('input').val() +'</p>' );


	/*
	** Free vs Pro Options Dropdown ---------------
	*/
	$('.wpr-pro-widgets > ul > li > span').on('click', function() {
		let options = $(this).parent('li').find('ul'),
			display = options.css('display');

		// Reset
		$('.wpr-pro-widgets ul ul').slideUp();

		// Show
		if ( 'none' === display ) {
			options.slideDown();
			if ( $(this).hasClass('wpr-premium-template-kit-lib') ) {
				setTimeout(function() {
					window.open('https://demosites.royal-elementor-addons.com/elementor-templates/?ref=rea-plugin-backend-freevsprotab-pro', '_blank');
				}, 600);
			}
			if ( $(this).hasClass('wpr-advanced-sticky-options') ) {
				setTimeout(function() {
					window.open('https://royal-elementor-addons.com/advanced-sticky-header/?ref=rea-plugin-backend-freevsprotab-pro', '_blank');
				}, 600);
			}
		} else {
			options.slideUp();
		}
	});

	/*
	** Free vs Pro Search -------------------------
	*/
	var searchTimeoutFP = null;  
	$('.wpr-free-pro-search').find('input').keyup(function(e) {
		if ( e.which === 13 ) {
			return false;
		}

		var val = $(this).val().toLowerCase();

		if (searchTimeoutFP != null) {
			clearTimeout(searchTimeoutFP);
		}

		searchTimeoutFP = setTimeout(function() {
			searchTimeoutFP = null;
			let visibleElements = 'none';

			// Reset
			$('.wpr-feature-not-found').hide();
			$('.wpr-free-pro-upgrade.last').show();

			if ( '' !== val ) {
				$('.wpr-free-vs-pro > div > ul > li').hide();
			} else {
				$('.wpr-free-vs-pro > div > ul > li').show();
			}

			$('.wpr-free-vs-pro > div > ul > li').each(function(){
				let title = $(this).find('span').text().toLowerCase();

				if ( -1 !== title.indexOf(val) ) {
					$(this).show();
					$(this).parent().show();
					visibleElements = 'visible';
				}
			});

			if ( 'none' === visibleElements ) {
				$('.wpr-feature-not-found').css('display', 'flex');
				$('.wpr-free-pro-upgrade.last').hide();
			}

			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {
					action: 'wpr_backend_freepro_search_query_results',
					search_query: val
				},
				success: function( response ) {}
			});
		}, 1000);  
	});

}); // end dom ready