"use strict";

function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }
function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _iterableToArrayLimit(arr, i) { var _i = null == arr ? null : "undefined" != typeof Symbol && arr[Symbol.iterator] || arr["@@iterator"]; if (null != _i) { var _s, _e, _x, _r, _arr = [], _n = !0, _d = !1; try { if (_x = (_i = _i.call(arr)).next, 0 === i) { if (Object(_i) !== _i) return; _n = !1; } else for (; !(_n = (_s = _x.call(_i)).done) && (_arr.push(_s.value), _arr.length !== i); _n = !0); } catch (err) { _d = !0, _e = err; } finally { try { if (!_n && null != _i["return"] && (_r = _i["return"](), Object(_r) !== _r)) return; } finally { if (_d) throw _e; } } return _arr; } }
function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }
function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e2) { throw _e2; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e3) { didErr = true; err = _e3; }, f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }
function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }
(function ($) {
  $(document).ready(function () {
    var widgets = document.querySelectorAll('.elementor-widget-ms_lms_courses');
    widgets.forEach(function (widget) {
      // variables init
      var widgetData = JSON.parse(widget.getAttribute('data-settings')),
        container = $(widget).find('.ms_lms_courses_archive'),
        containerGrid = $(widget).find('.ms_lms_courses_grid'),
        sliderContainer = $(widget).find('.ms_lms_courses_card_wrapper'),
        dropdownSelect = $(widget).find('.ms_lms_courses_archive__sorting.style_3'),
        dropdownSelectGrid = $(widget).find('.ms_lms_courses_grid__sorting.style_3'),
        dropdownSelectCarousel = $(widget).find('.ms_lms_courses_carousel__sorting.style_3'),
        filter = $(widget).find('.ms_lms_courses_archive__filter'),
        filterTitle = $(widget).find('.ms_lms_courses_archive__filter_options_item_title'),
        filterToggle = $(widget).find('.ms_lms_courses_archive__filter_toggle'),
        showMoreInstructors = $(widget).find('.ms_lms_courses_archive__filter_options_item_show-instructors'),
        filterSubmit = $(widget).find('.ms_lms_courses_archive__filter_actions_button'),
        filterReset = $(widget).find('.ms_lms_courses_archive__filter_actions_reset'),
        filterForm = $(widget).find('.ms_lms_courses_archive__filter_form'),
        paginationItem = $(widget).find('.ms_lms_courses_archive__pagination_list_item'),
        paginationItemGrid = $(widget).find('.ms_lms_courses_grid__pagination_list_item'),
        openedFilters = widgetData['opened_filters'],
        sortBy = widgetData['sort_by'],
        widgetType = widgetData['type'],
        carouselArgs = {
          'containerCarousel': $(widget).find('.ms_lms_courses_carousel'),
          'sliderContainer': sliderContainer,
          'carouselData': {
            'slides_to_scroll': widgetData['slides_to_scroll'],
            'slides_to_scroll_tablet': widgetData['slides_to_scroll_tablet'],
            'slides_to_scroll_mobile': widgetData['slides_to_scroll_mobile'],
            'autoplay': widgetData['autoplay'],
            'loop': widgetData['loop']
          },
          'slidesOptions': {
            '100%': 1,
            '50%': 2,
            '33.333333%': 3,
            '25%': 4,
            '20%': 5,
            '16.666666%': 6
          }
        },
        filterArgs = {
          'coursesContainer': $(widget).find('.ms_lms_courses_card:not(.featured)'),
          'paginationContainer': $(widget).find('.ms_lms_courses_archive__pagination_wrapper'),
          'paginationContainerGrid': $(widget).find('.ms_lms_courses_grid__pagination_wrapper'),
          'container': container,
          'containerGrid': containerGrid,
          'filterSubmit': filterSubmit,
          'filterForm': filterForm,
          'courseCardPresets': widgetData['course_card_presets'],
          'paginationPresets': widgetData['pagination_presets'],
          'cardsToShowChoice': widgetData['cards_to_show_choice'],
          'cardsToShow': widgetData['cards_to_show'],
          'courseImageSize': widgetData['course_image_size'],
          'metaSlots': {
            'card_slot_1': widgetData['card_slot_1'],
            'card_slot_2': widgetData['card_slot_2'],
            'card_slot_3': widgetData['card_slot_3'],
            'popup_slot_1': widgetData['popup_slot_1'],
            'popup_slot_2': widgetData['popup_slot_2'],
            'popup_slot_3': widgetData['popup_slot_3']
          },
          'cardData': {
            'show_popup': widgetData['show_popup'],
            'show_category': widgetData['show_category'],
            'show_excerpt': widgetData['show_excerpt'],
            'show_progress': widgetData['show_progress'],
            'show_divider': widgetData['show_divider'],
            'show_rating': widgetData['show_rating'],
            'show_price': widgetData['show_price'],
            'show_slots': widgetData['show_slots'],
            'show_wishlist': widgetData['show_wishlist'],
            'status_presets': widgetData['status_presets'],
            'status_position': widgetData['status_position'],
            'featured_position': widgetData['featured_position']
          },
          'popupData': {
            'popup_show_author_name': widgetData['popup_show_author_name'],
            'popup_show_author_image': widgetData['popup_show_author_image'],
            'popup_show_wishlist': widgetData['popup_show_wishlist'],
            'popup_show_price': widgetData['popup_show_price'],
            'popup_show_excerpt': widgetData['popup_show_excerpt'],
            'popup_show_slots': widgetData['popup_show_slots']
          },
          'showFeaturedBlock': widgetData['show_featured_block'],
          'sortByCat': widgetData['sort_by_cat'],
          'sortBy': sortBy,
          'widgetType': widgetType,
          'sliderContainer': sliderContainer
        },
        sortingArgs = {
          'sorting': $(widget).find('.ms_lms_courses_archive__sorting'),
          'sortingGrid': $(widget).find('.ms_lms_courses_grid__sorting'),
          'sortingSelect': $(widget).find('.ms_lms_courses_archive__sorting_select'),
          'sortingSelectGrid': $(widget).find('.ms_lms_courses_grid__sorting_select'),
          'sortingSelectCarousel': $(widget).find('.ms_lms_courses_carousel__sorting_select'),
          'sortingButton': $(widget).find('.ms_lms_courses_archive__sorting_button'),
          'sortingButtonGrid': $(widget).find('.ms_lms_courses_grid__sorting_button'),
          'sortingButtonCarousel': $(widget).find('.ms_lms_courses_carousel__sorting_button'),
          'sortBy': sortBy
        };

      // functions init
      sortingArgs['sortingSelect'].on('change', function (event) {
        onSortingSelectChange(event, filterArgs);
      });
      sortingArgs['sortingSelect'].select2({
        minimumResultsForSearch: Infinity,
        dropdownParent: dropdownSelect
      });
      sortingArgs['sortingSelectGrid'].on('change', function (event) {
        console.log('sorting grid');
        onSortingSelectChangeGrid(event, filterArgs);
      });
      sortingArgs['sortingSelectGrid'].select2({
        minimumResultsForSearch: Infinity,
        dropdownParent: dropdownSelectGrid
      });
      sortingArgs['sortingSelectCarousel'].on('change', function (event) {
        onSortingSelectChangeCarousel(event, filterArgs, carouselArgs);
      });
      sortingArgs['sortingSelectCarousel'].select2({
        minimumResultsForSearch: Infinity,
        dropdownParent: dropdownSelectCarousel
      });
      sortingArgs['sortingButton'].on('click', function (event) {
        onSortingButtonClick(event, filterArgs);
      });
      sortingArgs['sortingButtonGrid'].on('click', function (event) {
        onSortingButtonClickGrid(event, filterArgs);
      });
      sortingArgs['sortingButtonCarousel'].on('click', function (event) {
        onSortingButtonClickCarousel(event, filterArgs, carouselArgs);
      });
      filterTitle.on('click', function (event) {
        onFilterTitleClick(event);
      });
      if (window.matchMedia('(min-width: 1025px)').matches) {
        if (openedFilters > 0) {
          for (var i = 0; i < openedFilters; i++) {
            filterTitle.eq(i).trigger('click');
          }
        }
      }
      filterToggle.on('click', function (event) {
        onFilterToggleClick(event, filterForm);
      });
      showMoreInstructors.on('click', function (event) {
        onShowMoreInstructorsClick(event);
      });
      filterSubmit.on('click', function (event) {
        console.log('filter submit');
        onFilterSubmitClick(event, sortingArgs, filter, filterForm, filterArgs);
      });
      filterReset.on('click', function (event) {
        onFilterResetClick(event);
      });
      container.on('click', '.ms_lms_courses_archive__no-result_reset', function (event) {
        onFilterResetClick(event);
      });
      container.on('click', '.ms_lms_courses_archive__pagination_list_item a', function (event) {
        onPaginationButtonClick(event, paginationItem, sortingArgs, filterArgs);
      });
      container.on('click', '.ms_lms_courses_archive__load-more-button', function (event) {
        onLoadMoreButtonClick(event, sortingArgs, filterArgs);
      });
      containerGrid.on('click', '.ms_lms_courses_grid__pagination_list_item a', function (event) {
        onPaginationButtonClickGrid(event, paginationItemGrid, sortingArgs, filterArgs);
      });
      containerGrid.on('click', '.ms_lms_courses_grid__load-more-button', function (event) {
        onLoadMoreButtonClickGrid(event, sortingArgs, filterArgs);
      });
      sliderInit(widgetType, carouselArgs);
    });
  });
  window.addEventListener('load', function () {
    loadComingSoon();
  });
  function loadComingSoon() {
    var coming_soon_containers = document.querySelectorAll('.coming-soon-card-countdown-container');
    coming_soon_containers.forEach(function (container) {
      if (container.clientWidth < 220) {
        container.classList.add('smaller-container');
      } else if (container.clientWidth > 220) {
        container.classList.add('wider-container');
      }
    });
  }
  function sliderInit(widgetType, carouselArgs) {
    if (widgetType === 'courses-carousel') {
      CarouselInit(carouselArgs);
    }
  }
  function onSortingSelectChange(event, filterArgs) {
    var sort_by = $(event.currentTarget).val(),
      args = {};
    if (location.search) {
      args = getSearchArgs();
    }
    var current_page = 1;
    filtering(sort_by, args, current_page, false, filterArgs);
  }
  function onSortingSelectChangeGrid(event, filterArgs) {
    var sort_by = $(event.currentTarget).val(),
      current_page = 1;
    filteringGrid(sort_by, current_page, false, filterArgs);
  }
  function onSortingSelectChangeCarousel(event, filterArgs, carouselArgs) {
    var sort_by = $(event.currentTarget).val();
    filteringCarousel(sort_by, filterArgs, carouselArgs);
  }
  function onSortingButtonClick(event, filterArgs) {
    event.preventDefault();
    $(event.currentTarget).parent().siblings().find('.ms_lms_courses_archive__sorting_button').removeClass('active');
    $(event.currentTarget).addClass('active');
    var sort_by = $(event.currentTarget).data('id'),
      args = {};
    if (location.search) {
      args = getSearchArgs();
    }
    var current_page = 1;
    filtering(sort_by, args, current_page, false, filterArgs);
  }
  function onSortingButtonClickGrid(event, filterArgs) {
    event.preventDefault();
    $(event.currentTarget).parent().siblings().find('.ms_lms_courses_grid__sorting_button').removeClass('active');
    $(event.currentTarget).addClass('active');
    var sort_by = $(event.currentTarget).data('id');
    var current_page = 1;
    filteringGrid(sort_by, current_page, false, filterArgs);
  }
  function onSortingButtonClickCarousel(event, filterArgs, carouselArgs) {
    event.preventDefault();
    $(event.currentTarget).parent().siblings().find('.ms_lms_courses_carousel__sorting_button').removeClass('active');
    $(event.currentTarget).addClass('active');
    var sort_by = $(event.currentTarget).data('id');
    filteringCarousel(sort_by, filterArgs, carouselArgs);
  }
  function onFilterTitleClick(event) {
    $(event.currentTarget).toggleClass('active').next().slideToggle('medium', function () {
      if ($(event.currentTarget).is(':visible')) {
        $(event.currentTarget).css('display', 'flex');
      }
    });
  }
  function onFilterToggleClick(event, filterForm) {
    event.preventDefault();
    filterForm.slideToggle('medium', function () {
      if ($(event.currentTarget).is(':visible')) {
        $(event.currentTarget).css('display', 'flex');
      }
    });
  }
  function onShowMoreInstructorsClick(event) {
    var categories = $(event.currentTarget).closest('.ms_lms_courses_archive__filter_options_item_content').find('.ms_lms_courses_archive__filter_options_item_category');
    categories.slideDown('medium', function () {
      if ($(event.currentTarget).is(':visible')) {
        $(event.currentTarget).css('display', 'flex');
      }
    });
    $(event.currentTarget).slideUp();
  }
  function onFilterSubmitClick(event, sortingArgs, filter, filterForm, filterArgs) {
    event.preventDefault();
    var sort_by = getSortArgs(sortingArgs);
    var args = getFormArgs(filter, filterForm);
    var current_page = 1;
    filtering(sort_by, args, current_page, false, filterArgs);
  }
  function onFilterResetClick(event) {
    event.preventDefault();
    history.replaceState({}, '', location.origin + location.pathname);
    location.reload();
  }
  function onPaginationButtonClick(event, paginationItem, sortingArgs, filterArgs) {
    event.preventDefault();
    var sort_by = getSortArgs(sortingArgs),
      args = {},
      current_page = parseInt(paginationItem.find('.current').text());
    if (location.search) {
      args = getSearchArgs();
    }
    if (!$(event.currentTarget).hasClass('next') && !$(event.currentTarget).hasClass('prev')) {
      $(event.currentTarget).parent().siblings().find('.current').removeClass('current');
      $(event.currentTarget).addClass('current');
      current_page = parseInt($(event.currentTarget).text());
    } else if ($(event.currentTarget).hasClass('next')) {
      current_page = parseInt($(event.currentTarget).parent().siblings().find('.current').text());
      current_page++;
    } else if ($(event.currentTarget).hasClass('prev')) {
      current_page = parseInt($(event.currentTarget).parent().siblings().find('.current').text());
      current_page--;
    }
    filtering(sort_by, args, current_page, false, filterArgs);
  }
  function onPaginationButtonClickGrid(event, paginationItemGrid, sortingArgs, filterArgs) {
    event.preventDefault();
    var sort_by = getSortArgsGrid(sortingArgs),
      current_page = parseInt(paginationItemGrid.find('.current').text());
    if (!$(event.currentTarget).hasClass('next') && !$(event.currentTarget).hasClass('prev')) {
      $(event.currentTarget).parent().siblings().find('.current').removeClass('current');
      $(event.currentTarget).addClass('current');
      current_page = parseInt($(event.currentTarget).text());
    } else if ($(event.currentTarget).hasClass('next')) {
      current_page = parseInt($(event.currentTarget).parent().siblings().find('.current').text());
      current_page++;
    } else if ($(event.currentTarget).hasClass('prev')) {
      current_page = parseInt($(event.currentTarget).parent().siblings().find('.current').text());
      current_page--;
    }
    filteringGrid(sort_by, current_page, false, filterArgs);
  }
  function onLoadMoreButtonClick(event, sortingArgs, filterArgs) {
    event.preventDefault();
    var sort_by = getSortArgs(sortingArgs),
      args = {};
    if (location.search) {
      args = getSearchArgs();
    }
    var offset = parseInt($(event.currentTarget).data('offset'));
    filtering(sort_by, args, 1, offset, filterArgs);
  }
  function onLoadMoreButtonClickGrid(event, sortingArgs, filterArgs) {
    event.preventDefault();
    var sort_by = getSortArgsGrid(sortingArgs);
    var offset = parseInt($(event.currentTarget).data('offset'));
    filteringGrid(sort_by, 1, offset, filterArgs);
  }
  function getSortArgs(sortingArgs) {
    if (sortingArgs['sortingSelect'].length) {
      return sortingArgs['sortingSelect'].val();
    } else if (sortingArgs['sortingButton'].length) {
      return sortingArgs['sorting'].find('.active').data('id');
    }
    return sortingArgs['sortBy'];
  }
  function getSortArgsGrid(sortingArgs) {
    if (sortingArgs['sortingSelectGrid'].length) {
      return sortingArgs['sortingSelectGrid'].val();
    } else if (sortingArgs['sortingButtonGrid'].length) {
      return sortingArgs['sortingGrid'].find('.active').data('id');
    }
    return sortingArgs['sortBy'];
  }
  function getFormArgs(filter, filterForm) {
    if (filter.length) {
      var values = filterForm.serializeArray();
      var args = {
        'terms': [],
        'meta_query': {
          'status': [],
          'level': [],
          'availability': '',
          'instructor': [],
          'price': [],
          'rating': 0
        }
      };
      values.forEach(function (element) {
        if (element['name'] === 'rating') {
          args['meta_query']['rating'] = element['value'];
        } else if (element['name'] === 'availability') {
          args['meta_query']['availability'] = element['value'];
        } else if (element['name'] === 'category[]' || element['name'] === 'subcategory[]') {
          args['terms'].push(element['value']);
        } else {
          var index = element['name'].slice(0, -2);
          args['meta_query'][index].push(element['value']);
        }
      });
      return args;
    }
    return {};
  }
  function getSearchArgs() {
    if (location.search) {
      var args = {
        'terms': [],
        's': '',
        'meta_query': {
          'status': [],
          'level': [],
          'instructor': [],
          'price': [],
          'rating': 0
        }
      };
      var queryString = new URLSearchParams(location.search.split('?')[1]);
      var _iterator = _createForOfIteratorHelper(queryString.entries()),
        _step;
      try {
        for (_iterator.s(); !(_step = _iterator.n()).done;) {
          var item = _step.value;
          // if elementor preview mode
          if (item[0] === 'preview' || item[0] === 'preview_nonce' || item[0] === 'preview_id') {
            continue;
          } else {
            if (item[0] === 'rating') {
              args['meta_query']['rating'] = item[1];
            } else if (item[0] === 'terms[]') {
              args['terms'].push(item[1]);
            } else if (item[0] === 'search') {
              args['s'] = item[1];
            } else if (item[0] !== 'sort' && item[0] !== 'current_page') {
              var index = item[0].slice(0, -2);
              args['meta_query'][index].push(item[1]);
            }
          }
        }
      } catch (err) {
        _iterator.e(err);
      } finally {
        _iterator.f();
      }
      return args;
    }
    return {};
  }
  function filtering(sort_by, args, page, offset, filterArgs) {
    var courses_container = filterArgs['coursesContainer'],
      pagination_container = filterArgs['paginationContainer'],
      scroll_container = filterArgs['container'],
      filter_button = filterArgs['filterSubmit'],
      filter_form = filterArgs['filterForm'];
    urlAddParams(sort_by, args, page);
    $.ajax({
      url: ms_lms_courses_archive_filter.ajax_url,
      type: 'post',
      dataType: 'json',
      data: {
        action: 'ms_lms_courses_archive_filter',
        card_template: filterArgs['courseCardPresets'],
        pagination_template: filterArgs['paginationPresets'],
        args: args,
        current_page: page,
        offset: offset,
        sort_by: sort_by,
        cards_to_show_choice: filterArgs['cardsToShowChoice'],
        cards_to_show: filterArgs['cardsToShow'],
        course_image_size: filterArgs['courseImageSize'],
        meta_slots: filterArgs['metaSlots'],
        card_data: filterArgs['cardData'],
        popup_data: filterArgs['popupData'],
        widget_type: filterArgs['widgetType'],
        nonce: ms_lms_courses_archive_filter.nonce
      },
      beforeSend: function beforeSend() {
        courses_container.addClass('loading');
        if (filter_button.length) {
          filter_button.addClass('loading').attr('disabled', true);
        }
      },
      success: function success(data) {
        if (!offset) {
          if (typeof filterArgs['showFeaturedBlock'] !== 'undefined') {
            courses_container.empty();
          } else {
            courses_container.find('.ms_lms_courses_card_item:not(.featured)').remove();
          }
          if (window.matchMedia('(max-width: 1024px)').matches && filter_form.is(':visible')) {
            scroll_container = courses_container;
          }
          var containerTop = scroll_container.offset().top,
            window_top = $(window).scrollTop(),
            window_height = $(window).height(),
            window_bottom = window_top + window_height;
          if (!(containerTop >= $(window).scrollTop() && containerTop + scroll_container.height() <= window_bottom)) {
            $('html, body').animate({
              scrollTop: scroll_container.offset().top
            }, 500);
          }
        }
        pagination_container.empty();
        if (data.cards) {
          courses_container.append(data.cards);
          if (data.pagination) {
            pagination_container.append(data.pagination);
          }
        } else if (data.no_found) {
          courses_container.append(data.no_found);
        }
        courses_container.removeClass('loading');
        if (filter_button.length) {
          filter_button.removeClass('loading').attr('disabled', false);
        }
        loadComingSoon();
        jQuery('.masterstudy-countdown').each(function () {
          if (jQuery(this).find('.countDays').length === 0) {
            jQuery(this).countdown({
              timestamp: jQuery(this).data('timer')
            });
          }
        });
      }
    });
  }
  ;
  function filteringGrid(sort_by, page, offset, filterArgs) {
    var courses_container = filterArgs['coursesContainer'],
      pagination_container = filterArgs['paginationContainerGrid'],
      scroll_container = filterArgs['containerGrid'];
    $.ajax({
      url: ms_lms_courses_archive_filter.ajax_url,
      type: 'post',
      dataType: 'json',
      data: {
        action: 'ms_lms_courses_grid_sorting',
        card_template: filterArgs['courseCardPresets'],
        pagination_template: filterArgs['paginationPresets'],
        current_page: page,
        offset: offset,
        sort_by: sort_by,
        sort_by_cat: filterArgs['sortByCat'],
        sort_by_default: filterArgs['sortBy'],
        cards_to_show_choice: filterArgs['cardsToShowChoice'],
        cards_to_show: filterArgs['cardsToShow'],
        course_image_size: filterArgs['courseImageSize'],
        meta_slots: filterArgs['metaSlots'],
        card_data: filterArgs['cardData'],
        popup_data: filterArgs['popupData'],
        widget_type: filterArgs['widgetType'],
        nonce: ms_lms_courses_archive_filter.nonce
      },
      beforeSend: function beforeSend() {
        courses_container.addClass('loading');
      },
      success: function success(data) {
        if (!offset) {
          courses_container.find('.ms_lms_courses_card_item:not(.featured)').remove();
          var containerTop = scroll_container.offset().top,
            window_top = $(window).scrollTop(),
            window_height = $(window).height(),
            window_bottom = window_top + window_height;
          if (!(containerTop >= $(window).scrollTop() && containerTop + scroll_container.height() <= window_bottom)) {
            $('html, body').animate({
              scrollTop: scroll_container.offset().top
            }, 500);
          }
        }
        pagination_container.empty();
        if (data.cards) {
          courses_container.append(data.cards);
          if (data.pagination) {
            pagination_container.append(data.pagination);
          }
        }
        jQuery('.masterstudy-countdown').each(function () {
          if (jQuery(this).find('.countDays').length === 0) {
            jQuery(this).countdown({
              timestamp: jQuery(this).data('timer')
            });
          }
        });
        courses_container.removeClass('loading');
      }
    });
  }
  ;
  function filteringCarousel(sort_by, filterArgs, carouselArgs) {
    $.ajax({
      url: ms_lms_courses_archive_filter.ajax_url,
      type: 'post',
      dataType: 'json',
      data: {
        action: 'ms_lms_courses_carousel_sorting',
        card_template: filterArgs['courseCardPresets'],
        sort_by: sort_by,
        sort_by_cat: filterArgs['sortByCat'],
        sort_by_default: filterArgs['sortBy'],
        cards_to_show_choice: filterArgs['cardsToShowChoice'],
        cards_to_show: filterArgs['cardsToShow'],
        meta_slots: filterArgs['metaSlots'],
        course_image_size: filterArgs['courseImageSize'],
        card_data: filterArgs['cardData'],
        popup_data: filterArgs['popupData'],
        widget_type: filterArgs['widgetType'],
        nonce: ms_lms_courses_archive_filter.nonce
      },
      beforeSend: function beforeSend() {
        filterArgs['coursesContainer'].addClass('loading');
        var widgetID = filterArgs['sliderContainer'].closest('.elementor-widget-ms_lms_courses').data('id');
        if (document.querySelector("[data-id=\"".concat(widgetID, "\"] .swiper-container")).swiper) {
          document.querySelector("[data-id=\"".concat(widgetID, "\"] .swiper-container")).swiper.destroy();
        }
      },
      success: function success(data) {
        filterArgs['coursesContainer'].find('.ms_lms_courses_card_item:not(.featured)').remove();
        if (data.cards) {
          filterArgs['coursesContainer'].append(data.cards);
          CarouselInit(carouselArgs);
        }
        jQuery('.masterstudy-countdown').each(function () {
          if (jQuery(this).find('.countDays').length === 0) {
            jQuery(this).countdown({
              timestamp: jQuery(this).data('timer')
            });
          }
        });
        filterArgs['coursesContainer'].removeClass('loading');
      }
    });
  }
  ;
  function urlAddParams(sort_by, args, page) {
    var searchParams = new URLSearchParams();
    if (args['terms']) {
      args['terms'].forEach(function (value) {
        searchParams.append('terms[]', value);
      });
    }
    if (args['s']) {
      searchParams.append('search', args['s']);
    }
    if (args['meta_query']) {
      var _loop = function _loop() {
        var _Object$entries$_i = _slicedToArray(_Object$entries[_i], 2),
          key = _Object$entries$_i[0],
          value = _Object$entries$_i[1];
        if (value.length > 0) {
          if (key !== 'rating' && key !== 'availability') {
            value.forEach(function (param) {
              searchParams.append(key + '[]', param);
              {}
            });
          } else {
            searchParams.append(key, value);
          }
        }
      };
      for (var _i = 0, _Object$entries = Object.entries(args['meta_query']); _i < _Object$entries.length; _i++) {
        _loop();
      }
    }
    searchParams.append('sort', sort_by);
    searchParams.append('current_page', page);
    history.pushState({}, null, location.origin + location.pathname + '?' + searchParams.toString());
  }
  function CarouselInit(carouselArgs) {
    var autoplayData = false,
      widgetID = carouselArgs['sliderContainer'].closest('.elementor-widget-ms_lms_courses').data('id'),
      sliderContainerWrapper = document.querySelector("[data-id=\"".concat(widgetID, "\"] .ms_lms_courses_card_wrapper")),
      sliderButtonNext = document.querySelector("[data-id=\"".concat(widgetID, "\"] .ms_lms_courses_carousel__navigation_next")),
      sliderButtonPrev = document.querySelector("[data-id=\"".concat(widgetID, "\"] .ms_lms_courses_carousel__navigation_prev"));
    if (carouselArgs['carouselData']['autoplay']) {
      autoplayData = {
        delay: 2000
      };
    }
    if (carouselArgs['containerCarousel'].length !== 0) {
      var mySwiper = new Swiper(sliderContainerWrapper, {
        slidesPerView: carouselArgs['slidesOptions'][carouselArgs['carouselData']['slides_to_scroll']],
        watchSlidesProgress: true,
        breakpoints: {
          360: {
            slidesPerView: carouselArgs['slidesOptions'][carouselArgs['carouselData']['slides_to_scroll_mobile']]
          },
          768: {
            slidesPerView: carouselArgs['slidesOptions'][carouselArgs['carouselData']['slides_to_scroll_tablet']]
          },
          1025: {
            slidesPerView: carouselArgs['slidesOptions'][carouselArgs['carouselData']['slides_to_scroll']]
          }
        },
        loop: carouselArgs['carouselData']['loop'],
        autoplay: autoplayData,
        navigation: {
          nextEl: sliderButtonNext,
          prevEl: sliderButtonPrev
        }
      });
    }
  }
})(jQuery);