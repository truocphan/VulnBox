;(function($){
"use strict";


    var HTMegaMenuAdmin = {

        instance: [],
        menuId: 0,
        depth: 0,

        init: function() {
            this.menuButton();

             $( document )
                 .on( 'click.HTMegaMenuAdmin', '.htmegamenu-menu-settings-save', this.saveMenuOpt )
                 .on( 'click.HTMegaMenuAdmin', '.htmega-menu-trigger', this.openPopup )
                 .on( 'click.HTMegaMenuAdmin', '.htmega-menu-popup-close', this.closePopup )
                 .on( 'click.HTMegaMenuAdmin', '.htmega-menu-popup-close-btn', this.closePopup )
                 .on( 'click.HTMegaMenuAdmin', '.htmega-menu-submit-btn', this.saveMenuData );
        },

        saveMenuOpt: function() {
            var spinner = $(this).parent().find('.spinner');
            spinner.addClass('loading');
            HTMegaMenuAdmin.save_menu_options( $(this) );
        },

        save_menu_options: function( that ){
            var parent = that.parents("#HT_Mega_Menu_meta_box"),
                settings = {
                    'enable_menu': ( parent.find("#htmegamenu-menu-metabox-input-is-enabled").is(':checked') === true ) ? 'on' : 'off'
                };
                
            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action          : "HT_Mega_Menu_Panels_ajax_requests",
                    sub_action      : "save_menu_options",
                    settings        : settings,
                    menu_id         : $("#htmegamenu-metabox-input-menu-id").val(),
                    nonce           : HTMEGAMENU.nonce
                },
                cache: false,
                success: function(response) {
                    that.parent().find('.spinner').removeClass('loading');
                }
            });

        },
      

        menuButton: function(){
            var button = wp.template( 'htmenubutton' );

            $( '#menu-to-edit .menu-item' ).each( function() {
                var $this = $( this ),
                    depth = HTMegaMenuAdmin.getItemDepth( $this ),
                    id    = HTMegaMenuAdmin.getItemId( $this );

                $this.find( '.item-title' ).append( button( {
                    id: id,
                    depth: depth,
                    label: 'HT Mega Menu'
                } ) );
            });

        },

        getItemId: function( $item ) {
            var id = $item.attr( 'id' );
            return id.replace( 'menu-item-', '' );
        },

        getItemDepth: function( $item ) {
            var depthClass = $item.attr( 'class' ).match( /menu-item-depth-\d/ );
            if ( ! depthClass[0] ) {
                return 0;
            }
            return depthClass[0].replace( 'menu-item-depth-', '' );
        },

        openPopup: function() {
            var $this   = $( this ),
                id      = $this.data( 'item-id' ),
                depth   = $this.data( 'item-depth' ),
                popupid = '#htmega-popup-' + id,
                content = null,
                wrapper = wp.template( 'htmenupopup' );

                if ( ! HTMegaMenuAdmin.instance[ id ] ) {

                $('body').append('<div class="htmega-menu-loader"></div>');

                $.ajax({ 
                    url: ajaxurl,
                    type: 'GET',
                    dataType: 'JSON',
                    data: {
                        action          : "HT_Mega_Menu_Panels_ajax_requests",
                        sub_action      : "get_menu_options",
                        menu_item_id    : id,

                    },
                    cache: false,
                    beforeSend: function(){
                        $('.htmega-menu-loader').html('<span class="htmega-menu-loading-close"></span><div class="htmegamenus-css"><div style="width:100%;height:100%" class="htmegamenus-ripple"><div></div><div></div>');
                    },
                     success: function( response ) {

                        $( '.htmega-menu-loader' ).hide();

                        content = wrapper( {
                            id: id,
                            depth: depth,
                            content:response.data.content,
                            templatelist:response.data.temp_list,
                        } );

                        $( 'body' ).append( content );

                        var savebtn = $(popupid).find('.htmega-menu-submit-btn');

                        $('.htmega-color-picker-field').wpColorPicker({
                            change: function(event, ui) {
                                savebtn.removeClass('disabled').attr('disabled', false).text( HTMEGAMENU.button.text );
                            }
                        });
                        $( popupid+' .wp-picker-clear' ).on( 'click',function(){
                            savebtn.removeClass('disabled').attr('disabled', false).text( HTMEGAMENU.button.text );
                        });

                        var iconfield = $( popupid ).find('.htmega-menu-icon');
                        HTMegaMenuAdmin.init_fontpicker( iconfield );
                        HTMegaMenuAdmin.init_tab( '.htmega-menu-popup-tab-menu' );

                        $( popupid +' form.htmega-menu-data').on( 'keyup', 'input[type="text"]' , function() {
                            savebtn.removeClass('disabled').attr('disabled', false).text( HTMEGAMENU.button.text );
                        });
                        $( popupid +' form.htmega-menu-data').on( 'change', 'select.widefat' , function() {
                            savebtn.removeClass('disabled').attr('disabled', false).text( HTMEGAMENU.button.text );
                        });

                        $( popupid +' form.htmega-menu-data').on('change', 'select.htmenu-bg-type', function() {

                            if( this.value == 'gradient' ){
                                $(popupid+' .htmenu-gradient-field').show();
                                $(popupid+' .htmenu-default-field').css('border-width','1px');
                            }else{
                                $(popupid+' .htmenu-gradient-field').hide();
                                $(popupid+' .htmenu-default-field').css('border-width','0');
                            }
                        });

                        $( '.htmegamenu-pro' ).click(function() {
                            $( "#htmegapro-dialog" ).dialog({
                                modal: true,
                                minWidth: 500,
                                buttons: {
                                    Ok: function() {
                                      $( this ).dialog( "close" );
                                    }
                                }
                            });
                        });

                        $(".htmegamenu-pro .wp-picker-container .wp-color-result,.htmegamenu-pro input,.htmegamenu-pro select").attr("disabled", true);

                        $(".htmegamenu-pro .wp-picker-container").css({"z-index": "-1"});

                    },

                    complete: function( data ) {
                        $( 'body' ).removeClass('htmega-menu-loading');
                    },

                });

                HTMegaMenuAdmin.instance[ id ] = popupid;
            }

            $( HTMegaMenuAdmin.instance[ id ] ).removeClass( 'htmega-hide' );
        },

        closePopup: function( e ) {
            e.preventDefault();
            $( this ).closest( '.htmega-menu-popup' ).addClass( 'htmega-hide' );
        },

        saveMenuData: function(){
            var $this   = $( this ),
                id      = $this.data( 'id' );

            var $menu_form = $('#htmega-menu-form-'+id),
            $savebtn = $menu_form.find('.htmega-menu-submit-btn');

            $menu_form.on('submit', function( event ) {
                event.preventDefault();

                $.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    data: {
                        action          : "HT_Mega_Menu_Panels_ajax_requests",
                        sub_action      : "save_menu_settings",
                        settings        : $menu_form.serialize(),
                        menu_item_id    : id,
                        nonce           : HTMEGAMENU.nonce
                    },
                    cache: false,
                    beforeSend: function(){
                        $savebtn.text( HTMEGAMENU.button.lodingtext ).addClass('updating-message');
                    },
                    success: function( response ) {
                        $savebtn.removeClass('updating-message').addClass('disabled').attr('disabled', true).text( HTMEGAMENU.button.successtext );
                    },
                    complete: function( data ) {
                        $savebtn.removeClass('updating-message').addClass('disabled').attr('disabled', true).text( HTMEGAMENU.button.successtext );
                    },

                });

            });

        },
       
       init_fontpicker: function( $el ){

            $el.fontIconPicker({
                source: HTMEGAMENU.iconlist,
                emptyIcon: true,
                hasSearch: true,
                theme: 'fip-bootstrap'
            });

            $('.submit-add-to-menu').on('click', function(){
                $el.fontIconPicker({
                    source: HTMEGAMENU.iconlist,
                    emptyIcon: true,
                    hasSearch: true,
                    theme: 'fip-bootstrap'
                });
            })

        },

        init_tab: function( menu ){
            $( menu ).on('click', 'a', function (e) {
                e.preventDefault();
                var $this = $(this),
                $target = $this.data('target'),
                $tabPane = $this.closest( menu ).siblings('.htmega-menu-popup-tab-content').find('.htmega-menu-popup-tab-pane[data-id='+$target+']');
                $this.addClass('active').closest('li').siblings().find('a').removeClass('active');
                $tabPane.addClass('active').siblings().removeClass('active');
            })
        },
        
    };

    HTMegaMenuAdmin.init();
    
})(jQuery);