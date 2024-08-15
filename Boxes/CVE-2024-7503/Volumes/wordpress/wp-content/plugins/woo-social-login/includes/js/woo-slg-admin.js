"use strict";

jQuery(document).ready(function ($) {

    // For facebook authentication type 
    $('input[type=radio][name=woo_slg_auth_type_facebook]').change(function() {
        if (this.value == 'app') {
            $('.fb_graph').hide();
        }
        else if (this.value == 'graph') {
            $('.fb_graph').show();
        }
    });

    // For facebook authentication type 
    $('input[type=checkbox][name=woo_slg_enable_facebook]').change(function() {
        if (this.checked) {
            // Checkbox is checked
            $('.facebook_section_hide').show();
        } else {
            // Checkbox is unchecked
            $('.facebook_section_hide').hide();
        }
    });

    // For WordPress authentication type
    $('input[type=radio][name=woo_slg_auth_type_wordpresscom]').change(function() {
        if (this.value == 'app') {
            $('.wp_graph').hide();
        }
        else if (this.value == 'graph') {
            $('.wp_graph').show();
        }
    });

    // For wordpresscom authentication type 
    $('input[type=checkbox][name=woo_slg_enable_wordpresscom]').change(function() {
        if (this.checked) {
            // Checkbox is checked
            $('.wordpresscom_section_hide').show();
        } else {
            // Checkbox is unchecked
            $('.wordpresscom_section_hide').hide();
        }
    });

    // For twitter authentication type 
    $('input[type=radio][name=woo_slg_auth_type_twitter]').change(function() {
        if (this.value == 'app') {
            $('.twitter_graph').hide();
        }
        else if (this.value == 'graph') {
            $('.twitter_graph').show();
        }
    });

    // For twitter authentication type 
    $('input[type=checkbox][name=woo_slg_enable_twitter]').change(function() {
        if (this.checked) {
            // Checkbox is checked
            $('.twitter_section_hide').show();
        } else {
            // Checkbox is unchecked
            $('.twitter_section_hide').hide();
        }
    });

    // For linkedin authentication type 
    $('input[type=radio][name=woo_slg_auth_type_linkedin]').change(function() {
        if (this.value == 'app') {
            $('.linkedin_graph').hide();
        }
        else if (this.value == 'graph') {
            $('.linkedin_graph').show();
        }
    });

    // For linkedin authentication type 
    $('input[type=checkbox][name=woo_slg_enable_linkedin]').change(function() {
        if (this.checked) {
            // Checkbox is checked
            $('.linkedin_section_hide').show();
        } else {
            // Checkbox is unchecked
            $('.linkedin_section_hide').hide();
        }
    });

    // For github authentication type 
    $('input[type=radio][name=woo_slg_auth_type_github]').change(function() {
        if (this.value == 'app') {
            $('.github_graph').hide();
        }
        else if (this.value == 'graph') {
            $('.github_graph').show();
        }
    });

    // For github authentication type 
    $('input[type=checkbox][name=woo_slg_enable_github]').change(function() {
        if (this.checked) {
            // Checkbox is checked
            $('.github_section_hide').show();
        } else {
            // Checkbox is unchecked
            $('.github_section_hide').hide();
        }
    });

    // For yahoo authentication type 
    $('input[type=radio][name=woo_slg_auth_type_yahoo]').change(function() {
        if (this.value == 'app') {
            $('.yahoo_graph').hide();
        }
        else if (this.value == 'graph') {
            $('.yahoo_graph').show();
        }
    });

    // For yahoo authentication type 
    $('input[type=checkbox][name=woo_slg_enable_yahoo]').change(function() {
        if (this.checked) {
            // Checkbox is checked
            $('.yahoo_section_hide').show();
        } else {
            // Checkbox is unchecked
            $('.yahoo_section_hide').hide();
        }
    });

    // For Google authentication type 
    $('input[type=radio][name=woo_slg_auth_type_google]').change(function() {
        if (this.value == 'app') {
            $('.google_graph').hide();
        }
        else if (this.value == 'graph') {
            $('.google_graph').show();
        }
    });

    // For google authentication type 
    $('input[type=checkbox][name=woo_slg_enable_googleplus]').change(function() {
        if (this.checked) {
            // Checkbox is checked
            $('.googleplus_section_hide').show();
        } else {
            // Checkbox is unchecked
            $('.googleplus_section_hide').hide();
        }
    });

    // For Paypal authentication type 
    $('input[type=radio][name=woo_slg_auth_type_paypal]').change(function() {
        if (this.value == 'app') {
            $('.paypal_graph').hide();
        }
        else if (this.value == 'graph') {
            $('.paypal_graph').show();
        }
    });

    // For paypal authentication type 
    $('input[type=checkbox][name=woo_slg_enable_paypal]').change(function() {
        if (this.checked) {
            // Checkbox is checked
            $('.paypal_section_hide').show();
        } else {
            // Checkbox is unchecked
            $('.paypal_section_hide').hide();
        }
    });

    // For VK authentication type 
    $('input[type=radio][name=woo_slg_auth_type_vk]').change(function() {
        if (this.value == 'app') {
            $('.vk_graph').hide();
        }
        else if (this.value == 'graph') {
            $('.vk_graph').show();
        }
    });

    // For vk authentication type 
    $('input[type=checkbox][name=woo_slg_enable_vk]').change(function() {
        if (this.checked) {
            // Checkbox is checked
            $('.vk_section_hide').show();
        } else {
            // Checkbox is unchecked
            $('.vk_section_hide').hide();
        }
    });

    // For foursquare authentication type 
    $('input[type=radio][name=woo_slg_auth_type_foursquare]').change(function() {
        if (this.value == 'app') {
            $('.foursquare_graph').hide();
        }
        else if (this.value == 'graph') {
            $('.foursquare_graph').show();
        }
    });

    // For foursquare authentication type 
    $('input[type=checkbox][name=woo_slg_enable_foursquare]').change(function() {
        if (this.checked) {
            // Checkbox is checked
            $('.foursquare_section_hide').show();
        } else {
            // Checkbox is unchecked
            $('.foursquare_section_hide').hide();
        }
    });

    // For Windows Liv authentication type 
    $('input[type=radio][name=woo_slg_auth_type_windowslive]').change(function() {
        if (this.value == 'app') {
            $('.windowslive_graph').hide();
        }
        else if (this.value == 'graph') {
            $('.windowslive_graph').show();
        }
    });

    // For windowslive authentication type 
    $('input[type=checkbox][name=woo_slg_enable_windowslive]').change(function() {
        if (this.checked) {
            // Checkbox is checked
            $('.windowslive_section_hide').show();
        } else {
            // Checkbox is unchecked
            $('.windowslive_section_hide').hide();
        }
    });

    // For amazon authentication type 
    $('input[type=radio][name=woo_slg_auth_type_amazon]').change(function() {
        if (this.value == 'app') {
            $('.amazon_graph').hide();
        }
        else if (this.value == 'graph') {
            $('.amazon_graph').show();
        }
    });

    // For amazon authentication type 
    $('input[type=checkbox][name=woo_slg_enable_amazon]').change(function() {
        if (this.checked) {
            // Checkbox is checked
            $('.amazon_section_hide').show();
        } else {
            // Checkbox is unchecked
            $('.amazon_section_hide').hide();
        }
    });

    // For apple authentication type 
    $('input[type=checkbox][name=woo_slg_enable_apple]').change(function() {
        if (this.checked) {
            // Checkbox is checked
            $('.apple_section_hide').show();
        } else {
            // Checkbox is unchecked
            $('.apple_section_hide').hide();
        }
    });

    // For line authentication type 
    $('input[type=checkbox][name=woo_slg_enable_line]').change(function() {
        if (this.checked) {
            // Checkbox is checked
            $('.line_section_hide').show();
        } else {
            // Checkbox is unchecked
            $('.line_section_hide').hide();
        }
    });
    
    // For email authentication type 
    $('input[type=checkbox][name=woo_slg_enable_email]').change(function() {
        if (this.checked) {
            // Checkbox is checked
            $('.email_section_hide').show();
        } else {
            // Checkbox is unchecked
            $('.email_section_hide').hide();
        }
    });

    /*$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
    postboxes.add_postbox_toggles('woocommerce-social-login_page_woo-social-settings');*/

    $("#woo_slg_enable_email_varification").on("change", function () {
        if ($(this).is(":checked")) {
            $(".show_hide").show();
        } else {
            $(".show_hide").hide();
        }
    });

    $('input[type=radio][name=woo_slg_enable_email_varification_using]').change(function() {
        if (this.value == '2') {
            $("#email_description_for_body").hide();
        }
        else {
            $("#email_description_for_body").show();
        }
    });

    //sortable table
    jQuery('table.woo-slg-sortable tbody').sortable({
        items: 'tr.can-drag',
        cursor: 'move',
        axis: 'y',
        handle: 'td',
        scrollSensitivity: 40,
        helper: function (e, ui) {
            ui.children().each(function () {
                jQuery(this).width(jQuery(this).width());
            });
            ui.css('left', '0');
            return ui;
        },
        start: function (event, ui) {
            ui.item.css('background-color', '#f6f6f6');
        },
        stop: function (event, ui) {
            ui.item.removeAttr('style');
        }
    });

    //Media Uploader
    $(document).on('click', '.woo-slg-upload-file-button', function () {

        var imgfield, showfield;
        imgfield = jQuery(this).prev('input').attr('id');
        showfield = jQuery(this).parents('td').find('.woo-vou-img-view');

        if (typeof wp == "undefined" || WooVouAdminSettings.new_media_ui != '1') {// check for media uploader						

            tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');

            window.original_send_to_editor = window.send_to_editor;
            window.send_to_editor = function (html) {

                if (imgfield) {

                    var mediaurl = $('img', html).attr('src');
                    $('#' + imgfield).val(mediaurl);
                    showfield.html('<img src="' + mediaurl + '" />');
                    tb_remove();
                    imgfield = '';

                } else {

                    window.original_send_to_editor(html);

                }
            };
            return false;

        } else {

            var file_frame;
            

            //new media uploader
            var button = jQuery(this);

            

            // If the media frame already exists, reopen it.
            if (file_frame) {

                file_frame.open();
                return;
            }

            // Create the media frame.
            file_frame = wp.media.frames.file_frame = wp.media({
                frame: 'post',
                state: 'insert',
                
                multiple: false  // Set to true to allow multiple files to be selected
            });

            file_frame.on('menu:render:default', function (view) {
                // Store our views in an object.
                var views = {};

                // Unset default menu items
                view.unset('library-separator');
                view.unset('gallery');
                view.unset('featured-image');
                view.unset('embed');

                // Initialize the views in our view object.
                view.set(views);
            });

            // When an image is selected, run a callback.
            file_frame.on('insert', function () {

                // Get selected size from media uploader
                var selected_size = $('.attachment-display-settings .size').val();

                var selection = file_frame.state().get('selection');
                selection.each(function (attachment, index) {
                    attachment = attachment.toJSON();

                    // Selected attachment url from media uploader
                    var attachment_url = attachment.sizes[selected_size].url;

                    if (index == 0) {
                        // place first attachment in field
                        $('#' + imgfield).val(attachment_url);
                        showfield.html('<img src="' + attachment_url + '" />');

                    } else {
                        $('#' + imgfield).val(attachment_url);
                        showfield.html('<img src="' + attachment_url + '" />');
                    }
                });
            });

            // Finally, open the modal
            file_frame.open();
        }

    });

    // Hide and show settings fields on change selections
    $(document).on('change', '.woo_slg_social_btn_change', function () {

        var this_obj = $(this);

        if (this_obj.val() == 0) {

            $('.woo_slg_social_btn_image').parents('tr').show();
            $('.woo_slg_social_btn_text').parents('tr').hide();
        }

        if (this_obj.val() == 1) {

            $('.woo_slg_social_btn_text').parents('tr').show();
            $('.woo_slg_social_btn_image').parents('tr').hide();
        }
    });

    // Check if available settings available hide and show related settings
    if ($('.woo_slg_social_btn_change').length > 0) {
        $('.woo_slg_social_btn_change:checked').change();
    }

    woo_slg_toggle_admin_email();
    $(document).on('change','#woo_slg_email_notification_type',function () {

        woo_slg_toggle_admin_email();
    });

    $(document).on('change','#woo_slg_enable_notification',function () {
        woo_slg_toggle_admin_email();
    });

    function woo_slg_toggle_admin_email() {

        $('#woo_slg_send_new_account_email_to_admin').parents('li').show();
        if ($('#woo_slg_email_notification_type').length) {
            var email_type = $('#woo_slg_email_notification_type').val();
            if (email_type == 'woocommerce') {
                if ($('#woo_slg_enable_notification').is(':checked')) {
                    $('#woo_slg_enable_notification').parents('li').next().show();
                } else {
                    $('#woo_slg_enable_notification').parents('li').next().hide();
                }
            }
        } else {
            $('#woo_slg_enable_notification').parents('li').next().show();
        }
    }

    woo_slg_toggle_admin_gdpr_options();
    $(document).on('change','#woo_slg_enable_gdpr',function () {
        woo_slg_toggle_admin_gdpr_options();
    });

    function woo_slg_toggle_admin_gdpr_options() {

        if ($('#woo_slg_enable_gdpr').length) {

            if ($('#woo_slg_enable_gdpr').is(':checked')) {
                $('#woo_slg_gdpr_privacy_page').closest('tr').show();
                $('#woo_slg_gdpr_privacy_policy').closest('tr').show();
            } else {
                $('#woo_slg_gdpr_privacy_page').closest('tr').hide();
                $('#woo_slg_gdpr_privacy_policy').closest('tr').hide();
            }
        }

    }

    // This is for user agreement GDPR
    woo_slg_toggle_admin_ua_gdpr_options();
    $(document).on('change','#woo_slg_enable_gdpr_ua',function () {
        woo_slg_toggle_admin_ua_gdpr_options();
    });

    function woo_slg_toggle_admin_ua_gdpr_options() {

        if ($('#woo_slg_enable_gdpr_ua').length) {

            if ($('#woo_slg_enable_gdpr_ua').is(':checked')) {
                $('#woo_slg_gdpr_ua_page').closest('tr').show();
                $('#woo_slg_gdpr_user_agree').closest('tr').show();
            } else {
                $('#woo_slg_gdpr_ua_page').closest('tr').hide();
                $('#woo_slg_gdpr_user_agree').closest('tr').hide();
            }
        }

    }


    //Website Url Valid check
    $(document).on('click', '.woo-slg-save-btn', function () {

        var website_url = $('#woo_slg_redirect_url').val();

        if (website_url != '' && !woo_slg_is_url_valid(website_url)) {
            $('#general_tab').trigger("click");
            var websitecontent = $('#woo_slg_redirect_url').addClass('woo-slg-not-rec').focus();
            $('#woo_slg_redirect_url').parent().find('.woo-slg-hide').show();

            $('html, body').animate({scrollTop: websitecontent.offset().top - 50}, 500);
            return false;
        }
    });


    //  When user clicks on tab, this code will be executed
    $(document).on("click", ".nav-tab-wrapper a", function () {

        //  First remove class "active" from currently active tab
        $(".nav-tab-wrapper a").removeClass('nav-tab-active');

        //  Now add class "active" to the selected/clicked tab
        $(this).addClass("nav-tab-active");

        //  Hide all tab content
        $(".woo-slg-tab-content").hide();

        //  Here we get the href value of the selected tab
        var selected_tab = $(this).attr("href");

        //  Show the selected tab content
        $(selected_tab).show();

        var selected = selected_tab.split('-');
        $(".woo-slg-tab-content").removeClass('woo-slg-selected-tab');
        $('#woo_slg_selected_tab').val(selected[3]);

        //  At the end, we add return false so that the click on the link is not executed
        return false;
    });

    // Set the select2 for setting page
    $(".wslg-select").select2();

    //call on click reset options button from settings page
    $(document).on("click", "#woo_slg_reset_settings", function () {

        var ans;
        ans = confirm(WooVouAdminSettings.reset_settings_warning);

        if (ans) {
            return true;
        } else {
            return false;
        }
    });

    $(document).on('change', '#woo_slg_allow_peepso_avatar', function () {
        if ($(this).is(':checked')) {
            $('#peepso_avatar_each_time').show();
        } else {
            $('#peepso_avatar_each_time').hide();
        }
    });

    $(document).on('change', '#woo_slg_allow_peepso_cover', function () {
        if ($(this).is(':checked')) {
            $('#peepso_cover_each_time').show();
        } else {
            $('#peepso_cover_each_time').hide();
        }
    });

    $(document).on('change', 'select[name="woo_slg_social_btn_position"]', function () {
        if ($(this).val() == 'hook') {
            $('#woo-slg-social-btn-hooks-container').show();
        } else {
            $('#woo-slg-social-btn-hooks-container').hide();
        }
    });

    $(document).on('click', '#woo-slg-add-custom-hook', function () {
        var hook_input = '<li class="woo-slg-social-btn-custom-hook"><input type="text" name="woo_slg_social_btn_hooks[]" class="woo-slg-social-btn-hook regular-text"><button class="woo-slg-remove-custom-hook" type="button">X</button></li>';
        $(hook_input).appendTo("#custom-hooks-container");
    });

    $(document).on('click', '.woo-slg-remove-custom-hook', function () {
        $(this).parent().remove();
    });
    
   

}); // End of document ready function

// function to validate url
function woo_slg_is_url_valid(url) {
    return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);
}