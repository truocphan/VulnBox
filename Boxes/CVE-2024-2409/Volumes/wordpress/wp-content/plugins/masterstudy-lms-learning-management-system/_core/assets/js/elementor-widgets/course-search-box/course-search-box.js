"use strict";

(function ($) {
  $(document).ready(function () {
    $('.elementor-widget-ms_lms_courses_searchbox, .stm_lms_courses_search').each(function () {
      new Vue({
        el: $(this).find('.ms_lms_course_search_box')[0],
        data: function data() {
          return {
            search: '',
            url: ''
          };
        },
        components: {
          autocomplete: Vue2Autocomplete["default"]
        },
        methods: {
          searchCourse: function searchCourse(obj) {
            window.location.href = obj.url;
          },
          searching: function searching(value) {
            this.url = value;
          }
        }
      });
    });
    $('.ms_lms_course_search_box__categories_dropdown_parent').hover(function () {
      var menu = $('.ms_lms_course_search_box__categories_dropdown_childs_wrapper');
      var target = menu.find("[data-id='" + $(this).data('id') + "']");
      if (target.length > 0) {
        target.siblings().removeClass('visible');
        menu.addClass('visible');
        target.addClass('visible');
      } else {
        menu.removeClass('visible');
        menu.find('.visible').removeClass('visible');
      }
    });
    $('.ms_lms_course_search_box__categories_dropdown').mouseleave(function () {
      $('.ms_lms_course_search_box__categories_dropdown_childs_wrapper').removeClass('visible');
      $('.ms_lms_course_search_box__categories_dropdown_childs').removeClass('visible');
    });
    if ($('.ms_lms_course_search_box__popup_button').length > 0) {
      var popup = $('.ms_lms_course_search_box__popup');
      $('.ms_lms_course_search_box__popup_button').click(function (e) {
        e.preventDefault();
        popup.addClass('visible');
        $('body').addClass('ms_lms_course_search_box__popup_body');
      });
      $(document).on('click', function (e) {
        if (popup.hasClass('visible')) {
          if (e.target === popup[0]) {
            popup.removeClass('visible');
            $('body').removeClass('ms_lms_course_search_box__popup_body');
          }
        }
      });
      $('.ms_lms_course_search_box__categories').hover(function () {
        if (popup.hasClass('visible') && popup.hasClass('without_wrapper')) {
          $('.ms_lms_course_search_box__popup').addClass('ms_lms_searchbox_scroll');
        }
      }, function () {
        if (popup.hasClass('visible') && popup.hasClass('without_wrapper')) {
          $('.ms_lms_course_search_box__popup').removeClass('ms_lms_searchbox_scroll');
        }
      });
    }
    if ($('.ms_lms_course_search_box_compact').length > 0) {
      var compact_button = $('.ms_lms_course_search_box__compact_button'),
        search_wrapper = $('.ms_lms_course_search_box_compact_wrapper');
      compact_button.click(function (e) {
        if ($(this).hasClass('opening')) {
          e.preventDefault();
          var width = $('.search_button_compact').outerWidth();
          if ($('.ms_lms_course_search_box__categories').length > 0) {
            width += $('.ms_lms_course_search_box__categories').outerWidth();
          }
          $(this).removeClass('opening');
          search_wrapper.width(width);
          setTimeout(function () {
            search_wrapper.removeClass('closed');
          }, 1000);
        }
      });
      $(document).on('click', function (e) {
        if (!search_wrapper.hasClass('closed')) {
          if (!e.target.closest('.ms_lms_course_search_box_compact_wrapper')) {
            search_wrapper.addClass('closed').width(0);
            compact_button.addClass('opening');
          }
        }
      });
    }
    $('.ms_lms_course_search_box__categories_dropdown_parent_wrapper .mobile_chevron').click(function () {
      $(this).parent().parent().siblings().find('.ms_lms_course_search_box__categories_dropdown_mobile_childs').addClass('closed');
      $(this).parent().parent().siblings().find('.mobile_chevron').removeClass('opened');
      $(this).parent().next().toggleClass('closed');
      $(this).toggleClass('opened');
    });
  });
})(jQuery);