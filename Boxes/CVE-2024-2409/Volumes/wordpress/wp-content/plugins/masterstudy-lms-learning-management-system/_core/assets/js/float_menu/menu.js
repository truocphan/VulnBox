"use strict";

(function ($) {
  var $toggle = '';
  var $menu = '';
  var closeWidth = 1630;
  $(document).ready(function () {
    $toggle = $('.stm_lms_user_float_menu__toggle');
    $menu = $('.stm_lms_user_float_menu');
    $menu.css('padding-top', $('html').css('margin-top'));
    $toggle.on('click', function () {
      if ($menu.hasClass('__collapsed')) {
        openMenu();
      } else {
        closeMenu();
      }
    });
    $('.stm_lms_user_float_menu__login_head .masterstudy-button').on('click', function () {
      if ($('.masterstudy__login-page').length > 0) {
        closeMenu();
      }
    });
    windowWidth();
    menuPosition();
    collapsed_tip();
    tabs();
  });
  $(window).load(function () {
    scrollToActiveMenu();
    addScrollLabel();
  });

  // Check if width is changed (to prevent height change on android)
  var width = $(window).width();
  $(window).on('resize', function () {
    if ($(this).width() !== width) {
      width = $(this).width();
      windowWidth();
    }
  });
  function openMenu() {
    $menu.removeClass('__collapsed');
  }
  function closeMenu() {
    $menu.addClass('__collapsed');
  }
  function scrollToActiveMenu() {
    if (!$menu) $menu = $('.stm_lms_user_float_menu');
    if ($menu.find('.float_menu_item_active').length !== 0) {
      var $active_item = $menu.find('.float_menu_item_active');
      $('.stm_lms_user_float_menu__scrolled').animate({
        scrollTop: $active_item.position().top - 150
      }, 400);
    }
  }
  function windowWidth() {
    var winW = $(window).width();
    if (winW < closeWidth) {
      closeMenu();
    }
  }
  function menuPosition() {
    var position = $menu.hasClass('__position_left') ? 'left' : 'right';
    $('body').addClass("float_menu_position__".concat(position));
  }
  function collapsed_tip() {
    var $item = $('.float_menu_item__inline');
    var $tip = $('.stm_lms_user_float_menu__tip');
    $item.on('mouseover', function () {
      $tip.addClass('__visible');
      $tip.text($(this).find('.float_menu_item__title').text());
      $tip.css({
        top: $(this).offset().top - $(document).scrollTop() + 'px'
      });
    });
    $item.on('mouseout', function () {
      $tip.removeClass('__visible');
    });
  }
  function addScrollLabel() {
    var $scrollable = $('.stm_lms_user_float_menu__scrolled');
    var $scroll_label = $('.stm_lms_user_float_menu__scrolled_label');
    if (hasScrollBar($scrollable)) $menu.addClass('overflowed');
    $scrollable.on('scroll', function () {
      if ($scrollable.scrollTop() + $scrollable.innerHeight() + 5 >= $scrollable[0].scrollHeight) {
        $scroll_label.addClass('__hidden');
      } else {
        $scroll_label.removeClass('__hidden');
      }
    });
  }
  function hasScrollBar($scrollable) {
    return $scrollable.get(0).scrollHeight > $scrollable.outerHeight();
  }
  function tabs() {
    var $tab = $('.stm_lms_user_float_menu__tabs a');
    $tab.on('click', function (e) {
      e.preventDefault();
      var $this = $(this);
      $tab.removeClass('active');
      $this.addClass('active');

      /*Change tab*/
      $('.stm_lms_user_float_menu__scrolled > div').addClass('__hidden');
      $(".stm_lms_user_float_menu__scrolled > div".concat($this.attr('data-show'))).removeClass('__hidden');
    });
  }
})(jQuery);