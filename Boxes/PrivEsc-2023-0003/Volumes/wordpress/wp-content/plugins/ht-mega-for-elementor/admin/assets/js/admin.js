jQuery(document).ready(function($) {

    // Custom Tabs
    function htmega_admin_tabs( $tabmenus, $tabpane ){
        $tabmenus.on('click', 'a', function(e){
            e.preventDefault();
            var $this = $(this),
                $target = $this.attr('href');
            $this.addClass('httabactive').parent().siblings().children('a').removeClass('httabactive');
            $( $tabpane + $target ).addClass('httabactive').siblings().removeClass('httabactive');
        });
    }
    htmega_admin_tabs( $(".htmega-admin-tabs"), '.htmega-admin-tab-pane' );

    // Toggle Element
    function htmega_admin_toggle( $button, $area_element ){
        $button.on('click', function() {
            var inputCheckbox = $area_element.find('.htmega_table_row input[type="checkbox"]');
            if(inputCheckbox.prop("checked") === true){
                inputCheckbox.prop('checked', false)
            } else {
                inputCheckbox.prop('checked', true)
            }
        });
    }
    htmega_admin_toggle( $(".htmega-open-element-toggle"), $("#htmega_element_tabs") );
    htmega_admin_toggle( $(".htmega-open-element-toggle"), $("#htmega_thirdparty_element_tabs") );

   // facebook access token clear function
    $(".htmega-fb-clear-cache-btn").on('click', function(e) {
        var siteURL = site_url_data.site_url; // localize data
        e.preventDefault();
        $.ajax({
            url: siteURL+"/wp-admin/admin-ajax.php",
            data:{action:'my_delete_transient_action'},// form data
            method : 'POST',
            success:function(data){
                $(".htmega-admin-notify").html( "Cache has been cleared");
            }
        });
    });

// Coupon code copy function
// const couponButton = document.querySelector(".htoption-coupon-btn");
// const couponText = document.querySelector(".htoption-coupon-text");
//     couponButton.addEventListener("click", () => {
//         let textValue = couponText.value;
//         navigator.clipboard.writeText(textValue);
//         couponButton.classList.remove("htoption-btn-copy-status-copy");
//         couponButton.classList.add("htoption-btn-copy-status-copied");
//         setTimeout(() => {
//             couponButton.classList.remove("htoption-btn-copy-status-copied");
//             couponButton.classList.add("htoption-btn-copy-status-copy");
//         }, 2000);
//     });

// Send ajax request for newsletter subscription.
$( document ).on( 'click', '.htmega-admin-subscribe-form button[type="submit"]', function( e ) {

    e.preventDefault();

    let button = $( this ),
        form = button.closest( 'form' ),
        email = form.find( 'input[type="email"]' ).val(),
        buttonText = form.attr( 'data-htmega-button-text' ),
        processingText = form.attr( 'data-htmega-processing-text' ),
        completedText = form.attr( 'data-htmega-completed-text' ),
        ajaxErrorText = form.attr( 'data-htmega-ajax-error-text' ),
        statusWrap = form.closest( '.htmega-admin-subscribe-wrapper' ).find( '.htmega-subscribe-status' );

    $.ajax( {
        type: 'POST',
        url: ajaxurl,
        data: {
            action: 'htmega_newsletter_subscribe',
            email: email,
        },
        beforeSend: function() {
            button.html( processingText );
            form.addClass( 'htmega-admin-subscribe-processing' );
        },
        success: function( response ) {
            if ( ! response ) {
                form.removeClass( 'htmega-admin-subscribe-processing' );
                return;
            }

            if ( 'string' === typeof response ) {
                response = JSON.parse( response );
            }

            console.log( response );

            let resStatus = ( response.hasOwnProperty( 'status' ) ? response.status : 'error' ),
                resMessage = ( response.hasOwnProperty( 'message' ) ? response.message : ajaxErrorText );

            if ( 'success' === resStatus ) {
                button.html( completedText );
                form.addClass( 'htmega-admin-subscribe-success' );
                form.removeClass( 'htmega-admin-subscribe-error' );
            } else {
                button.html( buttonText );
                form.addClass( 'htmega-admin-subscribe-error' );
                form.removeClass( 'htmega-admin-subscribe-success' );
            }

            statusWrap.html( resMessage );
            form.removeClass( 'htmega-admin-subscribe-processing' );
        },
        error: function() {
            button.html( buttonText );
            statusWrap.html( ajaxErrorText );
            form.removeClass( 'htmega-admin-subscribe-processing' );
        },
    });
});

    // Footer Sticky Save Button
    var footerSaveStickyToggler = function () {
        // Footer Sticky Save Button
        var $adminHeaderArea  = $('.htmega-navigation-wrapper'),
            $stickyFooterArea = $('.htmega-opt-footer');
        if ( $stickyFooterArea.length <= 0 || $adminHeaderArea.length <= 0 ) return;
        var totalOffset = $adminHeaderArea.offset().top + $adminHeaderArea.outerHeight();
        var windowScroll    = $(window).scrollTop(),
            windowHeight    = $(window).height(),
            documentHeight  = $(document).height();

        if (totalOffset < windowScroll && windowScroll + windowHeight != documentHeight) {
            $stickyFooterArea.addClass('htmega-admin-sticky');
        } else if (windowScroll + windowHeight == documentHeight || totalOffset > windowScroll) {
            $stickyFooterArea.removeClass('htmega-admin-sticky');
        }
    };
    $(window).scroll(footerSaveStickyToggler);
    $(".htmega-navigation-menu li a").on('click', function() {
        $(window).scroll(footerSaveStickyToggler);
    });



});