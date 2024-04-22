"use strict";

(function ($) {
  $(document).ready(function () {
    $('body').on('click', '[data-field=wpcfto_addon_option_certificate_settings_title].wpcfto-box-child:not(.is_pro)', function () {
      $(this).closest('.wpcfto_group_started').toggleClass('open');
    });
    $('body').on('click', '.certificate_banner a.disabled', function (e) {
      e.preventDefault();
      var url = $(this).attr('href');
      var newUrl = document.querySelector('.data-url-certificate')['href'];
      ;
      $.ajax({
        url: url,
        success: function success() {
          window.location.href = newUrl;
        }
      });
    });
    $('body').on('click', '.open-settings', function (e) {
      window.location = location.origin + "/wp-admin/admin.php?page=stm-lms-settings#section_routes";
      window.location.reload(true);
    });
    $(".stm_lms_survey_plugin_notice").click(function (e) {
      e.preventDefault();
      var nonce = $(this).attr("data-nonce");
      var ajaxUrl = $(this).attr("admin-ajax-url");
      $.ajax({
        type: "post",
        dataType: "json",
        url: ajaxUrl,
        data: {
          action: 'stm_lms_survey_hide_notice_ajax',
          nonce: nonce
        },
        success: function success() {
          window.location.reload(true);
        }
      });
    });
    $('.stm-lms-unlock-pro-banner.addon_disabled').parents('.pro_banner').addClass('not_pro');
  });
})(jQuery);