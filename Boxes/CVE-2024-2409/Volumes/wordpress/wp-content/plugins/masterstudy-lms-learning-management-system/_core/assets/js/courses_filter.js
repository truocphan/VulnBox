"use strict";

function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
(function ($) {
  $(document).ready(function () {
    var $filter = $('.stm_lms_courses__filter');
    $filter.eq(0).addClass('active').find('.stm_lms_courses__filter_content').slideDown();
    $filter.eq(1).addClass('active').find('.stm_lms_courses__filter_content').slideDown();
    $filter.eq(2).addClass('active').find('.stm_lms_courses__filter_content').slideDown();
    $filter.eq(3).addClass('active').find('.stm_lms_courses__filter_content').slideDown();
    $filter.find('.stm_lms_courses__filter_heading').on('click', function () {
      $(this).closest('.stm_lms_courses__filter').toggleClass('active').find('.stm_lms_courses__filter_content').slideToggle();
    });
    $('.stm_lms_courses__filter input').each(function () {
      var value = $(this).val();
      var type = $(this).attr('type');
      if (type === 'checkbox' || type === 'radio') {
        value = $(this).is(':checked');
      }
      if (value) {
        $(this).closest('.stm_lms_courses__filter').addClass('active').find('.stm_lms_courses__filter_content').slideDown();
      }
    });
    subcategories();
    $('.stm_lms_courses__category .stm_lms_courses__filter_category input').on('change', function () {
      subcategories($(this));
    });
    courses_filter();
    $('.stm_lms_courses__archive_filter_toggle').on('click', function (e) {
      e.preventDefault();
      $('.stm_lms_courses__archive_filters').slideToggle();
    });
    limits();
    $('.reveal_limited').on('click', function () {
      $(this).slideUp();
      $(this).closest('.limited_list').find('.stm_lms_courses__filter_category').slideDown();
    });
  });
  function subcategories(category) {
    var categories = [];
    var subcategories = [];
    $('.stm_lms_courses__subcategory_item').hide();
    $('.stm_lms_courses__category .stm_lms_courses__filter_category input:checked').each(function () {
      var $this = $(this);
      categories.push($this.val());
    });
    if (categories.length) {
      categories.forEach(function (item) {
        if ($('.stm_lms_courses__subcategory_' + item + ' .stm_lms_courses__filter_category').length) {
          subcategories.push(item);
        }
      });
    }
    if (subcategories.length) {
      $('.stm_lms_courses__subcategory').show();
      subcategories.forEach(function (subcategory) {
        $('.stm_lms_courses__subcategory_' + subcategory).show();
      });
    } else {
      $('.stm_lms_courses__subcategory').hide();
    }

    /*Uncheck closed cateogories*/
    if (typeof category !== 'undefined') {
      var categoryChecked = category.is(':checked');
      var categoryVal = category.val();
      if (!categoryChecked) {
        $('.stm_lms_courses__subcategory_' + categoryVal + ' input').prop('checked', false);
      }
    }
  }
  function get_courses_filter_data() {
    var form_data = '?' + $('.stm_lms_courses__archive_filter form').serialize();
    return form_data;
  }
  function courses_filter() {
    var $form = $('.stm_lms_courses__archive_filter form');

    /** append search value to form **/
    $('#lms-search-input').on('change', function () {
      $form.find('input[name="search"]').val($(this).val());
      var suburl = get_courses_filter_data();
      history.pushState({}, null, location.origin + location.pathname + suburl);
    });
    $form.on('change', function () {
      var $this = $(this);
      var suburl = get_courses_filter_data();
      var $container = $this.closest('.stm_lms_courses_wrapper').find('.stm_lms_courses__archive');
      var $btn = $container.find('.stm_lms_load_more_courses');
      history.pushState({}, null, location.origin + location.pathname + suburl);
    });
    $form.on('submit', function (e) {
      e.preventDefault();
      var $this = $(this);
      load_content($this);
      load_content($this, true);
    });
  }
  function load_content($this) {
    var featured = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
    var $sort = $('.courses_filters .stm_lms_courses_grid__sort select');
    var $container = $this.closest('.stm_lms_courses_wrapper').find('.stm_lms_courses__archive');
    var $btn = $container.find('.stm_lms_load_more_courses');
    var $grid = $this.closest('.stm_lms_courses_wrapper').find('[data-pages]:last');
    if (featured) {
      $grid = $this.closest('.stm_lms_courses_wrapper').find('.featured-courses');
    }
    var template = $btn.attr('data-template');
    var args = $btn.attr('data-args');
    var suburl = '?' + $this.serialize();
    var sort_value = $sort.val();
    $btn.attr('data-url', suburl);
    $.ajax({
      url: stm_lms_ajaxurl + suburl,
      dataType: 'json',
      context: this,
      data: {
        offset: 0,
        template: template,
        args: args,
        sort: sort_value,
        featured: featured,
        action: 'stm_lms_load_content',
        nonce: stm_lms_nonces['load_content']
      },
      beforeSend: function beforeSend() {
        $grid.closest('.stm_lms_courses__archive').addClass('loading');
        $([document.documentElement, document.body]).animate({
          scrollTop: $('.stm_lms_courses__archive').offset().top - 130
        }, 1000);
      },
      complete: function complete(data) {
        data = data['responseJSON'];
        $grid.closest('.stm_lms_courses__archive').removeClass('loading');
        if (_typeof(data) === "object" && data.hasOwnProperty('search_title')) {
          $('.courses_filters__title').find('.lms-courses-search-result').html(data['search_title']);
        }

        /** if called for featured hide 'Featured header' if no data was found
         * Really bad practice in structure here in js and in template
         * NEED to refactor
         * **/
        if (true === featured && 0 === parseInt(data.total)) {
          $grid.parent().find('.featured-head').hide();
        } else if (true === featured && parseInt(data.total) > 0) {
          $grid.parent().find('.featured-head').show();
        }
        $grid.html(data['content']).attr('data-pages', data.pages);
        $btn.attr('data-offset', data['page']);
        hide_button($btn, data['page']);
        $('.masterstudy-countdown').each(function () {
          $(this).countdown({
            timestamp: $(this).data('timer')
          });
        });
      }
    });
  }
  function limits() {
    $('.limited_list').each(function () {
      $(this).find('input').each(function () {
        if ($(this).is(':checked')) {
          $(this).closest('.stm_lms_courses__filter_category').slideDown();
        }
      });
    });
  }
})(jQuery);