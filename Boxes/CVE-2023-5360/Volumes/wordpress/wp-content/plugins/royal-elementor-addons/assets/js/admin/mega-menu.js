jQuery(document).ready(function( $ ) {
	"use strict";

    var WprMegaMenuSettings = {

        getLiveSettings: WprMegaMenuSettingsData.settingsData,

        init: function() {
            WprMegaMenuSettings.initSettingsButtons();
        },

        initSettingsButtons: function() {
            $( '#menu-to-edit .menu-item' ).each( function() {
                var $this = $(this),
                    id = WprMegaMenuSettings.getNavItemId($this),
                    depth = WprMegaMenuSettings.getNavItemDepth($this);
                    
                // Settings Button
                $this.append('<div class="wpr-mm-settings-btn" data-id="'+ id +'" data-depth="'+ depth +'"><span>R</span>Mega Menu</div>');
            });
            
            // Open Popup
            $('.wpr-mm-settings-btn').on( 'click', WprMegaMenuSettings.openSettingsPopup );
        },

        openSettingsPopup: function() {
            // Set Settings
            WprMegaMenuSettings.setSettings( $(this) );

            // Show Popup
            $('.wpr-mm-settings-popup-wrap').fadeIn();

            // Close Temmplate Editor Popup
            WprMegaMenuSettings.closeTemplateEditorPopup();

            // Menu Width
            WprMegaMenuSettings.initMenuWidthToggle();

            // Mobile Content
            WprMegaMenuSettings.initMobileContentToggle();

            // Color Pickers
            WprMegaMenuSettings.initColorPickers();

            // Icon Picker
            WprMegaMenuSettings.initIconPicker();

            // Close Settings Popup
            WprMegaMenuSettings.closeSettingsPopup();

            // Save Settings
            WprMegaMenuSettings.saveSettings( $(this) );

            // Edit Menu Button
            WprMegaMenuSettings.initEditMenuButton( $(this) );

            // Set Tite
            $('.wpr-mm-popup-title').find('span').text( $(this).closest('li').find('.menu-item-title').text() );
        },

        closeSettingsPopup: function() {
            $('.wpr-mm-settings-close-popup-btn').on('click', function() {
                $('.wpr-mm-settings-popup-wrap').fadeOut();
            });

            $('.wpr-mm-settings-popup-wrap').on('click', function(e) {
                if(e.target !== e.currentTarget) return;
                $(this).fadeOut();
            });

            // Unbind Click
            $('.wpr-save-mega-menu-btn').off('click');
            $('.wpr-edit-mega-menu-btn').off('click');
        },

        initEditMenuButton: function( selector ) {
            $('.wpr-edit-mega-menu-btn').on('click', function() {
                var id = selector.attr('data-id'),
                    depth = selector.attr('data-depth');

                WprMegaMenuSettings.createOrEditMenuTemplate(id, depth);
            });
        },

		createOrEditMenuTemplate: function(id, depth) {
			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {
					action: 'wpr_create_mega_menu_template',
                    nonce: WprMegaMenuSettingsData.nonce,
                    item_id: id,
                    item_depth: depth
				},
				success: function( response ) {
                    console.log(response.data['edit_link']);
                    WprMegaMenuSettings.openTemplateEditorPopup(response.data['edit_link']);
				}
			});
		},

        openTemplateEditorPopup: function( editorLink ) {
            $('.wpr-mm-editor-popup-wrap').fadeIn();

            if ( !$('.wpr-mm-editor-popup-iframe').find('iframe').length ) {
                $('.wpr-mm-editor-popup-iframe').append('<iframe src="'+ editorLink +'" width="100%" height="100%"></iframe>');
            }

            // $('body').css('overflow','hidden');
        },

        closeTemplateEditorPopup: function() {
            $('.wpr-mm-editor-close-popup-btn').on('click', function() {
                $('.wpr-mm-editor-popup-wrap').fadeOut();
                setTimeout(function() {
                    $('.wpr-mm-editor-popup-iframe').find('iframe').remove();
                    // $('body').css('overflow','visible');
                }, 1000);
            });
        },

        initColorPickers: function() {
            $('.wpr-mm-setting-color').find('input').wpColorPicker();

            // Fix Color Picker
            if ( $('.wpr-mm-setting-color').length ) {
                $('.wpr-mm-setting-color').find('.wp-color-result-text').text('Select Color');
                $('.wpr-mm-setting-color').find('.wp-picker-clear').val('Clear');
            }
        },

        initIconPicker: function() {
            $('#wpr_mm_icon_picker').iconpicker();

            // Bind iconpicker events to the element
            $('#wpr_mm_icon_picker').on('iconpickerSelected', function(event) {
                $('.wpr-mm-setting-icon div span').removeClass('wpr-mm-active-icon');
                $('.wpr-mm-setting-icon div span:last-child').addClass('wpr-mm-active-icon');
                $('.wpr-mm-setting-icon div span:last-child i').removeAttr('class');
                $('.wpr-mm-setting-icon div span:last-child i').addClass(event.iconpickerValue);
            });

            // Bind iconpicker events to the element
            $('#wpr_mm_icon_picker').on('iconpickerHide', function(event) {
                setTimeout(function() {
                    if ( 'wpr-mm-active-icon' == $('.wpr-mm-setting-icon div span:first-child').attr('class') ) {
                        $('#wpr_mm_icon_picker').val('')
                    }

                    $('.wpr-mm-settings-wrap').removeAttr('style');
                },100);
            });

            $('.wpr-mm-setting-icon div span:first-child').on('click', function() {
                $('.wpr-mm-setting-icon div span').removeClass('wpr-mm-active-icon');
                $(this).addClass('wpr-mm-active-icon');
            });

            $('.wpr-mm-setting-icon div span:last-child').on('click', function() {
                $('#wpr_mm_icon_picker').focus();
                $('.wpr-mm-settings-wrap').css('overflow', 'hidden');
            });
        },

        saveSettings: function( selector ) {
            var $saveButton = $('.wpr-save-mega-menu-btn');

            // Reset
            $saveButton.text('Save');

            $saveButton.on('click', function() {
                var id = selector.attr('data-id'),
                    depth = selector.attr('data-depth'),
                    settings = WprMegaMenuSettings.getSettings();

                $.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    data: {
                        action: 'wpr_save_mega_menu_settings',
                        nonce: WprMegaMenuSettingsData.nonce,
                        item_id: id,
                        item_depth: depth,
                        item_settings: settings
                    },
                    success: function( response ) {
                        $saveButton.text('Saved');
                        $saveButton.append('<span class="dashicons dashicons-yes"></span>');

                        setTimeout(function() {
                            $saveButton.find('.dashicons').remove();
                            $saveButton.text('Save');
                            $saveButton.blur();
                        }, 1000);

                        // Update Settings
                        WprMegaMenuSettings.getLiveSettings[id] = settings;
                    }
                });
            });
            
        },

        getSettings: function() {
            var settings = {};

            $('.wpr-mm-setting').each(function() {
                var $this = $(this),
                    checkbox = $this.find('input[type="checkbox"]'),
                    select = $this.find('select'),
                    number = $this.find('input[type="number"]'),
                    text = $this.find('input[type="text"]');

                // Checkbox
                if ( checkbox.length ) {
                    let id = checkbox.attr('id');
                    settings[id] = checkbox.prop('checked') ? 'true' : 'false';
                }

                // Select
                if ( select.length ) {
                    let id = select.attr('id');
                    settings[id] = select.val();
                }
                
                // Multi Value
                // if ( $this.hasClass('wpr-mm-setting-radius') ) {
                //     let multiValue = [],
                //         id = $this.find('input').attr('id');

                //     $this.find('input').each(function() {
                //         multiValue.push($(this).val());
                //     });

                //     settings[id] = multiValue;
                // }

                // Number
                if ( number.length ) {
                    let id = number.attr('id');
                    settings[id] = number.val();
                }
                
                // Text
                if ( text.length ) {
                    let id = text.attr('id');

                    if ( 'wpr_mm_icon_picker' !== id ) {
                        settings[id] = text.val();
                    } else {
                        let icon_class = $('.wpr-mm-setting-icon div span.wpr-mm-active-icon').find('i').attr('class');
                        settings[id] = 'fas fa-ban' !== icon_class ? icon_class : '';
                    }
                }
            });

            return settings;
        },

		getNavItemId: function( item ) {
			var id = item.attr( 'id' );
			return id.replace( 'menu-item-', '' );
		},

		getNavItemDepth: function( item ) {
			var depthClass = item.attr( 'class' ).match( /menu-item-depth-\d/ );

			if ( ! depthClass[0] ) {
				return 0;
			} else {
                return depthClass[0].replace( 'menu-item-depth-', '' );
            }
		},

        initMenuWidthToggle: function() {
            var select = $('#wpr_mm_width'),
                option = $('#wpr_mm_custom_width').closest('.wpr-mm-setting');
            
            if ( 'custom' === select.val() ) {
                option.show();
            } else {
                option.hide();
            }

            select.on('change', function() {
                if ( 'custom' === select.val() ) {
                    option.show();
                } else {
                    option.hide();
                }            
            });
        },

        initMobileContentToggle: function() {
            var select = $('#wpr_mm_mobile_content'),
                option = $('#wpr_mm_render').closest('.wpr-mm-setting');
            
            if ( 'mega' === select.val() ) {
                option.show();
            } else {
                option.hide();
            }

            select.on('change', function() {
                if ( 'mega' === select.val() ) {
                    option.show();
                } else {
                    option.hide();
                }            
            });
        },

        setSettings: function( selector ) {
            var id = selector.attr('data-id'),
                settings = WprMegaMenuSettings.getLiveSettings[id];

            if ( ! $.isEmptyObject(settings) ) {
                // General
                if ( 'true' == settings['wpr_mm_enable'] ) {
                    $('#wpr_mm_enable').prop( 'checked', true );
                } else {
                    $('#wpr_mm_enable').prop( 'checked', false );
                }
                $('#wpr_mm_position').val(settings['wpr_mm_position']).trigger('change');
                $('#wpr_mm_width').val(settings['wpr_mm_width']).trigger('change');
                $('#wpr_mm_custom_width').val(settings['wpr_mm_custom_width']);
                $('#wpr_mm_render').val(settings['wpr_mm_render']).trigger('change');
                $('#wpr_mm_mobile_content').val(settings['wpr_mm_mobile_content']).trigger('change');

                // Icon
                if ( '' !== settings['wpr_mm_icon_picker'] ) {
                    $('.wpr-mm-setting-icon div span').removeClass('wpr-mm-active-icon');
                    $('.wpr-mm-setting-icon div span:last-child').addClass('wpr-mm-active-icon');
                    $('.wpr-mm-setting-icon div span:last-child i').removeAttr('class');
                    $('.wpr-mm-setting-icon div span:last-child i').addClass(settings['wpr_mm_icon_picker']);
                } else {
                    $('.wpr-mm-setting-icon div span').removeClass('wpr-mm-active-icon');
                    $('.wpr-mm-setting-icon div span:first-child').addClass('wpr-mm-active-icon');
                    $('.wpr-mm-setting-icon div span:last-child i').removeAttr('class');
                    $('.wpr-mm-setting-icon div span:last-child i').addClass('fas fa-angle-down');
                }
                $('#wpr_mm_icon_color').val(settings['wpr_mm_icon_color']).trigger('keyup');
                $('#wpr_mm_icon_size').val(settings['wpr_mm_icon_size']);

                // Badge
                $('#wpr_mm_badge_text').val(settings['wpr_mm_badge_text']);
                $('#wpr_mm_badge_color').val(settings['wpr_mm_badge_color']).trigger('keyup');
                $('#wpr_mm_badge_bg_color').val(settings['wpr_mm_badge_bg_color']).trigger('keyup');
                if ( 'true' == settings['wpr_mm_badge_animation'] ) {
                    $('#wpr_mm_badge_animation').prop( 'checked', true );
                } else {
                    $('#wpr_mm_badge_animation').prop( 'checked', false );
                }

            // Default Values
            } else {
                // General
                $('#wpr_mm_enable').prop( 'checked', false );
                $('#wpr_mm_position').val('default').trigger('change');
                $('#wpr_mm_width').val('default').trigger('change');
                $('#wpr_mm_custom_width').val('600');
                $('#wpr_mm_render').val('default').trigger('change');
                $('#wpr_mm_mobile_content').val('mega').trigger('change');

                // Icon
                if ( '' !== settings['wpr_mm_icon_picker'] ) {
                    $('.wpr-mm-setting-icon div span').removeClass('wpr-mm-active-icon');
                    $('.wpr-mm-setting-icon div span:first-child').addClass('wpr-mm-active-icon');
                    $('.wpr-mm-setting-icon div span:last-child i').removeAttr('class');
                    $('.wpr-mm-setting-icon div span:last-child i').addClass('fas fa-angle-down');
                }
                $('#wpr_mm_icon_color').val('').trigger('change');
                $('#wpr_mm_icon_size').val('');

                // Badge
                $('#wpr_mm_badge_text').val('');
                $('#wpr_mm_badge_color').val('#ffffff').trigger('keyup');
                $('#wpr_mm_badge_bg_color').val('#000000').trigger('keyup');
                $('#wpr_mm_badge_animation').prop( 'checked', false );
            }

            if ( 'false' === $('.wpr-mm-settings-wrap').attr('data-pro-active') ) {
                $('#wpr_mm_render').val('default').trigger('change');
                $('#wpr_mm_mobile_content').val('mega').trigger('change');

                // Icon
                if ( '' !== settings['wpr_mm_icon_picker'] ) {
                    $('.wpr-mm-setting-icon div span').removeClass('wpr-mm-active-icon');
                    $('.wpr-mm-setting-icon div span:first-child').addClass('wpr-mm-active-icon');
                    $('.wpr-mm-setting-icon div span:last-child i').removeAttr('class');
                    $('.wpr-mm-setting-icon div span:last-child i').addClass('fas fa-angle-down');
                }
                $('#wpr_mm_icon_color').val('').trigger('change');
                $('#wpr_mm_icon_size').val('');

                // Badge
                $('#wpr_mm_badge_text').val('');
                $('#wpr_mm_badge_color').val('#ffffff').trigger('keyup');
                $('#wpr_mm_badge_bg_color').val('#000000').trigger('keyup');
                $('#wpr_mm_badge_animation').prop( 'checked', false );
            }
        }
    }

    // Init
    WprMegaMenuSettings.init();

});