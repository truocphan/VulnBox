"use strict";

(function ($) {
  $(document).ready(function () {
    var classes = ['post-type-stm-courses', 'post-type-stm-lessons', 'post-type-stm-quizzes', 'post-type-stm-questions', 'post-type-stm-assignments', 'post-type-stm-google-meets', 'post-type-stm-user-assignment', 'post-type-stm-reviews', 'post-type-stm-orders', 'post-type-stm-ent-groups', 'post-type-stm-payout', 'taxonomy-stm_lms_course_taxonomy', 'taxonomy-stm_lms_question_taxonomy', 'stm-lms_page_stm-lms-online-testing', 'admin_page_stm_lms_scorm_settings', 'toplevel_page_stm-lms-dashboard'];
    var $settings_parent = $('.stm-lms-settings-menu-title').closest('li');
    $settings_parent.nextAll('li').addClass('stm-lms-pro-addons-menu');
    $settings_parent.addClass('stm-lms-settings-menu');
    $settings_parent.next('li').addClass('stm-lms-addons-page-menu');
    if ($('li.stm-lms-pro-addons-menu:last').find('span.stm-lms-unlock-pro-btn').length > 0) {
      $('li.stm-lms-pro-addons-menu:last').addClass('upgrade');
    }
    if ($('body').is("." + classes.join(', .'))) {
      $('#adminmenu > li').removeClass('wp-has-current-submenu wp-menu-open').find('wp-sumenu').css({
        'margin-right': 0
      });
      $('#toplevel_page_stm-lms-settings').addClass('wp-has-current-submenu wp-menu-open').removeClass('wp-not-current-submenu');
      $('.toplevel_page_stm-lms-settings').addClass('wp-has-current-submenu').removeClass('wp-not-current-submenu');
    }

    // unlock banner slider
    var slidePosition = 0;
    var numOfSlide = $("#unlock-slider-slide-holder > div").size();
    $("#unlock-slider-slide-holder").css("width", numOfSlide * 100 + "%");
    $(".unlock-slider-slide").css("width", 100 / numOfSlide + "%");
    for (var a = 0; a < numOfSlide; a++) {
      $('#unlock-slider-slide-nav').append(' <a href="javascript: void(0)" class="unlock-slider-slide-nav-bt' + (a === 0 ? ' active' : '') + '">  </a> ');
    }
    $('body').on('click', '.unlock-slider-slide-nav-bt', function () {
      moveSlide($(this));
      clearInterval(autoPlaySlideInter);
    });
    function moveSlide(thisa) {
      var thisindex = $('#unlock-slider-slide-nav a').index(thisa);
      $('#unlock-slider-slide-holder').css("margin-left", '-' + thisindex + '00%');
      $('#unlock-slider-slide-nav a').removeClass('active');
      thisa.addClass('active');
    }
    function autoPlaySlide() {
      slidePosition++;
      if (slidePosition == numOfSlide) {
        slidePosition = 0;
      }
      moveSlide($("#unlock-slider-slide-nav").children(".unlock-slider-slide-nav-bt:eq(" + slidePosition + ")"));
    }
    var autoPlaySlideInter = setInterval(autoPlaySlide, 4000);
  });
})(jQuery);