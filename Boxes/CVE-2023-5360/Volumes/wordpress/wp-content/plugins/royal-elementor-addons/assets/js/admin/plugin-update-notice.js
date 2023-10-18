jQuery( document ).ready( function() {

    if ( jQuery('#wpr-notice-confetti').length ) {
        const wprConfetti = confetti.create( document.getElementById('wpr-notice-confetti'), {
            resize: true
        });

        setTimeout( function () {
            wprConfetti( {
                particleCount: 150,
                origin: { x: 1, y: 2 },
                gravity: 0.3,
                spread: 50,
                ticks: 150,
                angle: 120,
                startVelocity: 60,
                colors: [
                    '#0e6ef1',
                    '#f5b800',
                    '#ff344c',
                    '#98e027',
                    '#9900f1',
                ],
            } );
        }, 500 );

        setTimeout( function () {
            wprConfetti( {
                particleCount: 150,
                origin: { x: 0, y: 2 },
                gravity: 0.3,
                spread: 50,
                ticks: 200,
                angle: 60,
                startVelocity: 60,
                colors: [
                    '#0e6ef1',
                    '#f5b800',
                    '#ff344c',
                    '#98e027',
                    '#9900f1',
                ],
            } );
        }, 900 );
    }

    // Update Notice
    jQuery(document).on( 'click', '.wpr-plugin-update-notice .notice-dismiss', function() {
        jQuery(document).find('.wpr-plugin-update-notice').slideUp();
        console.log('works update dismiss');
        jQuery.post({
            url: WprPluginNotice.ajaxurl,
            data: {
                nonce: WprPluginNotice.nonce,
                action: 'wpr_plugin_update_dismiss_notice',
            }
        });
    });

    // Plugin Sale Notice
    jQuery(document).on( 'click', '.wpr-plugin-sale-notice .notice-dismiss', function() {
        jQuery(document).find('.wpr-plugin-sale-notice').slideUp();
        jQuery.post({
            url: ajaxurl,
            data: {
                nonce: WprPluginNotice.nonce,
                action: 'wpr_plugin_sale_dismiss_notice'
            }
        });
    });
    
    jQuery(document).on( 'click', '.wpr-plugin-sale-notice .wpr-remind-later', function(e) {
        e.preventDefault();
        jQuery(document).find('.wpr-plugin-sale-notice').slideUp();
        jQuery.post({
            url: ajaxurl,
            data: {
                nonce: WprPluginNotice.nonce,
                action: 'wpr_sale_remind_me_later',
            }
        });
    });

    jQuery(document).on( 'click', '.wpr-pro-features-notice .notice-dismiss', function() {

        jQuery('body').removeClass('wpr-pro-features-body');

        jQuery(document).find('.wpr-pro-features-notice-wrap').fadeOut();
        jQuery(document).find('.wpr-pro-features-notice').slideUp();
        jQuery.post({
            url: ajaxurl,
            data: {
                nonce: WprPluginNotice.nonce,
                action: 'wpr_pro_features_dismiss_notice'
            }
        });
    });

    // Rating Notice
    jQuery(document).on( 'click', '.wpr-notice-dismiss-2', function() {
        jQuery(document).find('.wpr-rating-notice').slideUp();
        jQuery.post({
            url: ajaxurl,
            data: {
                nonce: WprPluginNotice.nonce,
                action: 'wpr_rating_dismiss_notice',
            }
        })
    });

    jQuery(document).on( 'click', '.wpr-maybe-later', function() {
        jQuery(document).find('.wpr-rating-notice').slideUp();
        jQuery.post({
            url: ajaxurl,
            data: {
                nonce: WprPluginNotice.nonce,
                action: 'wpr_rating_maybe_later',
            }
        })
    });

    jQuery(document).on( 'click', '.wpr-already-rated', function() {
        jQuery(document).find('.wpr-rating-notice').slideUp();
        jQuery.post({
            url: ajaxurl,
            data: {
                nonce: WprPluginNotice.nonce,
                action: 'wpr_rating_already_rated',
            }
        })
    });

    jQuery(document).on( 'click', '.wpr-need-support', function() {
        jQuery.post({
            url: ajaxurl,
            data: {
                nonce: WprPluginNotice.nonce,
                action: 'wpr_rating_need_help',
            }
        })
    });

});