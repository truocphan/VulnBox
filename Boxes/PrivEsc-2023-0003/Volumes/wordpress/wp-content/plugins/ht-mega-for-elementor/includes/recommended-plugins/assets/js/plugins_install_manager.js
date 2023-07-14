/**
 * Plugins Install manager JS
 */
;( function ( $ ) {
    'use strict';

    // Tab Menu
    $(".htrp-admin-tabs").on('click', 'a', function(e){
        e.preventDefault();
        var $this = $(this),
            $target = $this.attr('href');

        $this.addClass('htrp-active').parent().siblings().children('a').removeClass('htrp-active');
        $( '.htrp-admin-tab-pane'+ $target ).addClass('htrp-active').siblings().removeClass('htrp-active');
    });

    /*
    * Plugin Installation Manager
    */
    var PluginInstallManager = {

        init: function(){
            $( document ).on('click','.install-now', PluginInstallManager.installNow );
            $( document ).on('click','.activate-now', PluginInstallManager.activatePlugin);
            $( document ).on('wp-plugin-install-success', PluginInstallManager.installingSuccess);
            $( document ).on('wp-plugin-install-error', PluginInstallManager.installingError);
            $( document ).on('wp-plugin-installing', PluginInstallManager.installingProcess);
        },

        /**
         * Installation Error.
         */
        installingError: function( e, response ) {
            e.preventDefault();
            var $card = $( '.htrp-plugin-' + response.slug );
            $button = $card.find( '.button' );
            $button.removeClass( 'button-primary' ).addClass( 'disabled' ).html( wp.updates.l10n.installFailedShort );
        },

        /**
         * Installing Process
         */
        installingProcess: function(e, args){
            e.preventDefault();
            var $card = $( '.htrp-plugin-' + args.slug ),
                $button = $card.find( '.button' );
                $button.text( htrp_params.buttontxt.installing ).addClass( 'updating-message' );
        },

        /**
        * Plugin Install Now
        */
        installNow: function(e){
            e.preventDefault();

            var $button = $( e.target ),
                $plugindata = $button.data('pluginopt');

            if ( $button.hasClass( 'updating-message' ) || $button.hasClass( 'button-disabled' ) ) {
                return;
            }
            if ( wp.updates.shouldRequestFilesystemCredentials && ! wp.updates.ajaxLocked ) {
                wp.updates.requestFilesystemCredentials( e );
                $( document ).on( 'credential-modal-cancel', function() {
                    var $message = $( '.install-now.updating-message' );
                    $message.removeClass( 'updating-message' ).text( wp.updates.l10n.installNow );
                    wp.a11y.speak( wp.updates.l10n.updateCancel, 'polite' );
                });
            }
            wp.updates.installPlugin( {
                slug: $plugindata['slug']
            });

        },

        /**
         * After Plugin Install success
         */
        installingSuccess: function( e, response ) {
            var $message = $( '.htrp-plugin-' + response.slug ).find( '.button' );

            var $plugindata = $message.data('pluginopt');

            $message.removeClass( 'install-now installed button-disabled updated-message' )
                .addClass( 'updating-message' )
                .html( htrp_params.buttontxt.activating );

            setTimeout( function() {
                $.ajax( {
                    url: htrp_params.ajaxurl,
                    type: 'POST',
                    data: {
                        action   : htrp_params.text_domain+'_ajax_plugin_activation',
                        location : $plugindata['location'],
                        nonce    : htrp_params.nonce
                    },
                } ).done( function( result ) {
                    if ( result.success ) {
                        $message.removeClass( 'button-primary install-now activate-now updating-message' )
                            .attr( 'disabled', 'disabled' )
                            .addClass( 'disabled' )
                            .text( htrp_params.buttontxt.active );

                    } else {
                        $message.removeClass( 'updating-message' );
                    }

                });

            }, 1200 );

        },

        /**
         * Plugin Activate
         */
        activatePlugin: function( e, response ) {
            e.preventDefault();

            var $button = $( e.target ),
                $plugindata = $button.data('pluginopt');

            if ( $button.hasClass( 'updating-message' ) || $button.hasClass( 'button-disabled' ) ) {
                return;
            }

            $button.addClass( 'updating-message button-primary' ).html( htrp_params.buttontxt.activating );
            $.ajax( {
                url: htrp_params.ajaxurl,
                type: 'POST',
                data: {
                    action   : htrp_params.text_domain+'_ajax_plugin_activation',
                    location : $plugindata['location'],
                    nonce    : htrp_params.nonce
                },
            }).done( function( response ) {
                if ( response.success ) {
                    $button.removeClass( 'button-primary install-now activate-now updating-message' )
                        .attr( 'disabled', 'disabled' )
                        .addClass( 'disabled' )
                        .text( htrp_params.buttontxt.active );
                }
            });

        },

        
    };

    /**
     * Initialize PluginInstallManager
     */
    $( document ).ready( function() {
        PluginInstallManager.init();
    });

} )( jQuery );