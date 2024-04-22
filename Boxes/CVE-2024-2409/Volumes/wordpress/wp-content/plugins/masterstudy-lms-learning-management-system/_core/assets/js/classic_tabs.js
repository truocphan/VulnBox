"use strict";

(function ($) {
  var $tabs = '';
  $(document).ready(function () {
    $tabs = $('.stm_lms_account_classic_tabs .stm-lms-user_create_announcement_btn a');
    var currentUrl = location.href;
    var lastChar = currentUrl.slice(-1);
    if (lastChar === '/' || lastChar === '#') currentUrl = currentUrl.substring(0, currentUrl.length - 1);
    var tab = $('.stm-lms-user_create_announcement_btn a[href="' + currentUrl + '"]');
    if (tab.length) tab.addClass('active');
    $tabs.on('click', function (e) {
      var $wrapper = $(this).closest('.stm-lms-user_create_announcement_btn');
      var isJsTab = $wrapper.attr('data-container');
      if (typeof isJsTab !== 'undefined') {
        $tabs.removeClass('active');
        $(this).addClass('active');
      }
    });
  });
  $(window).on('load', function () {
    var currentHash = location.hash;
    if (currentHash) {
      currentHash = currentHash.replace('#', '.');
      var $hashTab = $('.stm-lms-user_create_announcement_btn[data-container="' + currentHash + '"]');
      if ($hashTab.length) {
        $tabs.removeClass('active');
        $hashTab.find('a').addClass('active').trigger('click');
      }
    }
  });
})(jQuery);