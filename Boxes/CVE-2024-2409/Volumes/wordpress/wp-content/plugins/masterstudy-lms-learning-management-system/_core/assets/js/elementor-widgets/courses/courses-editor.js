"use strict";

function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }
function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _iterableToArrayLimit(arr, i) { var _i = null == arr ? null : "undefined" != typeof Symbol && arr[Symbol.iterator] || arr["@@iterator"]; if (null != _i) { var _s, _e, _x, _r, _arr = [], _n = !0, _d = !1; try { if (_x = (_i = _i.call(arr)).next, 0 === i) { if (Object(_i) !== _i) return; _n = !1; } else for (; !(_n = (_s = _x.call(_i)).done) && (_arr.push(_s.value), _arr.length !== i); _n = !0); } catch (err) { _d = !0, _e = err; } finally { try { if (!_n && null != _i["return"] && (_r = _i["return"](), Object(_r) !== _r)) return; } finally { if (_d) throw _e; } } return _arr; } }
function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }
function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e2) { throw _e2; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e3) { didErr = true; err = _e3; }, f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }
function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }
function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); Object.defineProperty(subClass, "prototype", { writable: false }); if (superClass) _setPrototypeOf(subClass, superClass); }
function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf ? Object.setPrototypeOf.bind() : function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }
function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = _getPrototypeOf(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = _getPrototypeOf(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return _possibleConstructorReturn(this, result); }; }
function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } else if (call !== void 0) { throw new TypeError("Derived constructors may only return object or undefined"); } return _assertThisInitialized(self); }
function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }
function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }
function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf.bind() : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }
var MsLmsCourses = /*#__PURE__*/function (_elementorModules$fro) {
  _inherits(MsLmsCourses, _elementorModules$fro);
  var _super = _createSuper(MsLmsCourses);
  function MsLmsCourses() {
    _classCallCheck(this, MsLmsCourses);
    return _super.apply(this, arguments);
  }
  _createClass(MsLmsCourses, [{
    key: "getDefaultSettings",
    value: function getDefaultSettings() {
      return {
        selectors: {
          container: '.ms_lms_courses_archive',
          containerGrid: '.ms_lms_courses_grid',
          containerCarousel: '.ms_lms_courses_carousel',
          sliderContainer: '.ms_lms_courses_card_wrapper',
          sorting: '.ms_lms_courses_archive__sorting',
          sortingGrid: '.ms_lms_courses_grid__sorting',
          sortingCarousel: '.ms_lms_courses_carousel__sorting',
          sortingSelect: '.ms_lms_courses_archive__sorting_select',
          sortingSelectGrid: '.ms_lms_courses_grid__sorting_select',
          sortingSelectCarousel: '.ms_lms_courses_carousel__sorting_select',
          dropdownSelect: '.ms_lms_courses_archive__sorting.style_3',
          dropdownSelectGrid: '.ms_lms_courses_grid__sorting.style_3',
          dropdownSelectCarousel: '.ms_lms_courses_carousel__sorting.style_3',
          sortingButton: '.ms_lms_courses_archive__sorting_button',
          sortingButtonGrid: '.ms_lms_courses_grid__sorting_button',
          sortingButtonCarousel: '.ms_lms_courses_carousel__sorting_button',
          sortingButtonActive: '.ms_lms_courses_archive__sorting_button.active',
          sortingButtonActiveGrid: '.ms_lms_courses_grid__sorting_button.active',
          sortingButtonActiveCarousel: '.ms_lms_courses_carousel__sorting_button.active',
          filter: '.ms_lms_courses_archive__filter',
          filterTitle: '.ms_lms_courses_archive__filter_options_item_title',
          filterToggle: '.ms_lms_courses_archive__filter_toggle',
          showMoreInstructors: '.ms_lms_courses_archive__filter_options_item_show-instructors',
          filterSubmit: '.ms_lms_courses_archive__filter_actions_button',
          filterReset: '.ms_lms_courses_archive__filter_actions_reset',
          filterForm: '.ms_lms_courses_archive__filter_form',
          paginationItem: '.ms_lms_courses_archive__pagination_list_item',
          paginationItemGrid: '.ms_lms_courses_grid__pagination_list_item',
          paginationContainer: '.ms_lms_courses_archive__pagination_wrapper',
          paginationContainerGrid: '.ms_lms_courses_grid__pagination_wrapper',
          coursesContainer: '.ms_lms_courses_card:not(.featured)'
        }
      };
    }
  }, {
    key: "getDefaultElements",
    value: function getDefaultElements() {
      var selectors = this.getSettings('selectors');
      var elementSettings = this.getElementSettings();
      return {
        $container: this.$element.find(selectors.container),
        $containerGrid: this.$element.find(selectors.containerGrid),
        $containerCarousel: this.$element.find(selectors.containerCarousel),
        $sliderContainer: this.$element.find(selectors.sliderContainer),
        $sorting: this.$element.find(selectors.sorting),
        $sortingGrid: this.$element.find(selectors.sortingGrid),
        $sortingCarousel: this.$element.find(selectors.sortingCarousel),
        $sortingSelect: this.$element.find(selectors.sortingSelect),
        $sortingSelectGrid: this.$element.find(selectors.sortingSelectGrid),
        $sortingSelectCarousel: this.$element.find(selectors.sortingSelectCarousel),
        $dropdownSelect: this.$element.find(selectors.dropdownSelect),
        $dropdownSelectGrid: this.$element.find(selectors.dropdownSelectGrid),
        $dropdownSelectCarousel: this.$element.find(selectors.dropdownSelectCarousel),
        $sortingButton: this.$element.find(selectors.sortingButton),
        $sortingButtonGrid: this.$element.find(selectors.sortingButtonGrid),
        $sortingButtonCarousel: this.$element.find(selectors.sortingButtonCarousel),
        $sortingButtonActive: this.$element.find(selectors.sortingButtonActive),
        $sortingButtonActiveGrid: this.$element.find(selectors.sortingButtonActiveGrid),
        $sortingButtonActiveCarousel: this.$element.find(selectors.sortingButtonActiveCarousel),
        $filterTitle: this.$element.find(selectors.filterTitle),
        $filterToggle: this.$element.find(selectors.filterToggle),
        $showMoreInstructors: this.$element.find(selectors.showMoreInstructors),
        $filter: this.$element.find(selectors.filter),
        $filterSubmit: this.$element.find(selectors.filterSubmit),
        $filterReset: this.$element.find(selectors.filterReset),
        $filterForm: this.$element.find(selectors.filterForm),
        $paginationItem: this.$element.find(selectors.paginationItem),
        $paginationItemGrid: this.$element.find(selectors.paginationItemGrid),
        $paginationContainer: this.$element.find(selectors.paginationContainer),
        $paginationContainerGrid: this.$element.find(selectors.paginationContainerGrid),
        $coursesContainer: this.$element.find(selectors.coursesContainer),
        $openedFilters: elementSettings['opened_filters'],
        $sortBy: elementSettings['sort_by'],
        $sortByCat: elementSettings['sort_by_cat'],
        $showFeaturedBlock: elementSettings['show_featured_block'],
        $courseCardPresets: elementSettings['course_card_presets'],
        $paginationPresets: elementSettings['pagination_presets'],
        $cardsToShowChoice: elementSettings['cards_to_show_choice'],
        $cardsToShow: elementSettings['cards_to_show'],
        $metaSlots: {
          'card_slot_1': elementSettings['card_slot_1'],
          'card_slot_2': elementSettings['card_slot_2'],
          'card_slot_3': elementSettings['card_slot_3'],
          'popup_slot_1': elementSettings['popup_slot_1'],
          'popup_slot_2': elementSettings['popup_slot_2'],
          'popup_slot_3': elementSettings['popup_slot_3']
        },
        $cardData: {
          'show_popup': elementSettings['show_popup'],
          'show_category': elementSettings['show_category'],
          'show_excerpt': elementSettings['show_excerpt'],
          'show_progress': elementSettings['show_progress'],
          'show_divider': elementSettings['show_divider'],
          'show_rating': elementSettings['show_rating'],
          'show_price': elementSettings['show_price'],
          'show_slots': elementSettings['show_slots'],
          'show_wishlist': elementSettings['show_wishlist'],
          'status_presets': elementSettings['status_presets'],
          'status_position': elementSettings['status_position'],
          'featured_position': elementSettings['featured_position']
        },
        $popupData: {
          'popup_show_author_name': elementSettings['popup_show_author_name'],
          'popup_show_author_image': elementSettings['popup_show_author_image'],
          'popup_show_wishlist': elementSettings['popup_show_wishlist'],
          'popup_show_price': elementSettings['popup_show_price'],
          'popup_show_excerpt': elementSettings['popup_show_excerpt'],
          'popup_show_slots': elementSettings['popup_show_slots']
        },
        $slidesOptions: {
          '100%': 1,
          '50%': 2,
          '33.333333%': 3,
          '25%': 4,
          '20%': 5,
          '16.666666%': 6
        },
        $carouselData: {
          'slides_to_scroll': elementSettings['slides_to_scroll'],
          'slides_to_scroll_tablet': elementSettings['slides_to_scroll_tablet'],
          'slides_to_scroll_mobile': elementSettings['slides_to_scroll_mobile'],
          'autoplay': elementSettings['autoplay'],
          'loop': elementSettings['loop']
        },
        $widgetType: elementSettings['type']
      };
    }
  }, {
    key: "bindEvents",
    value: function bindEvents() {
      jQuery('.masterstudy-countdown').each(function () {
        if (jQuery(this).find('.countDays').length === 0) {
          jQuery(this).countdown({
            timestamp: jQuery(this).data('timer')
          });
        }
      });
      var coming_soon_containers = window.self.document.querySelectorAll('.coming-soon-card-countdown-container');
      coming_soon_containers.forEach(function (container) {
        container.classList.add('smaller-container');
      });
      this.elements.$sortingSelect.on('change', this.onSortingSelectChange.bind(this));
      this.elements.$sortingSelect.select2({
        minimumResultsForSearch: Infinity,
        dropdownParent: this.elements.$dropdownSelect
      });
      this.elements.$sortingSelectGrid.on('change', this.onSortingSelectChangeGrid.bind(this));
      this.elements.$sortingSelectGrid.select2({
        minimumResultsForSearch: Infinity,
        dropdownParent: this.elements.$dropdownSelectGrid
      });
      this.elements.$sortingSelectCarousel.on('change', this.onSortingSelectChangeCarousel.bind(this));
      this.elements.$sortingSelectCarousel.select2({
        minimumResultsForSearch: Infinity,
        dropdownParent: this.elements.$dropdownSelectCarousel
      });
      this.elements.$sortingButton.on('click', this.onSortingButtonClick.bind(this));
      this.elements.$sortingButtonGrid.on('click', this.onSortingButtonClickGrid.bind(this));
      this.elements.$sortingButtonCarousel.on('click', this.onSortingButtonClickCarousel.bind(this));
      this.elements.$filterTitle.on('click', this.onFilterTitleClick.bind(this));
      if (window.matchMedia('(min-width: 1025px)').matches) {
        if (this.elements.$openedFilters > 0) {
          for (var i = 0; i < this.elements.$openedFilters; i++) {
            this.elements.$filterTitle.eq(i).trigger('click');
          }
        }
      }
      this.elements.$filterToggle.on('click', this.onFilterToggleClick.bind(this));
      this.elements.$showMoreInstructors.on('click', this.onShowMoreInstructorsClick.bind(this));
      this.elements.$filterSubmit.on('click', this.onFilterSubmitClick.bind(this));
      this.elements.$filterReset.on('click', this.onFilterResetClick.bind(this));
      this.elements.$container.on('click', '.ms_lms_courses_archive__no-result_reset', this.onFilterResetClick.bind(this));
      this.elements.$container.on('click', '.ms_lms_courses_archive__pagination_list_item a', this.onPaginationButtonClick.bind(this));
      this.elements.$container.on('click', '.ms_lms_courses_archive__load-more-button', this.onLoadMoreButtonClick.bind(this));
      this.elements.$containerGrid.on('click', '.ms_lms_courses_grid__pagination_list_item a', this.onPaginationButtonClickGrid.bind(this));
      this.elements.$containerGrid.on('click', '.ms_lms_courses_grid__load-more-button', this.onLoadMoreButtonClickGrid.bind(this));
      jQuery(document).ready(this.sliderInit.bind(this));
    }
  }, {
    key: "sliderInit",
    value: function sliderInit() {
      if (typeof ms_lms_courses_archive_filter.editor !== 'undefined') {
        jQuery('.ms_lms_courses_card_wrapper').addClass('editor-visible');
        jQuery('.ms_lms_courses_carousel .ms_lms_courses_card_item').addClass('editor-visible');
      }
      this.CarouselInit();
    }
  }, {
    key: "onSortingSelectChange",
    value: function onSortingSelectChange(event) {
      if (typeof ms_lms_courses_archive_filter.editor !== 'undefined') {
        return;
      }
      var sort_by = jQuery(event.currentTarget).val(),
        args = {};
      if (location.search) {
        args = this.getSearchArgs();
      }
      var current_page = 1;
      this.filtering(sort_by, args, current_page, false);
    }
  }, {
    key: "onSortingSelectChangeGrid",
    value: function onSortingSelectChangeGrid(event) {
      if (typeof ms_lms_courses_archive_filter.editor !== 'undefined') {
        return;
      }
      var sort_by = jQuery(event.currentTarget).val();
      var current_page = 1;
      this.filteringGrid(sort_by, current_page, false);
    }
  }, {
    key: "onSortingSelectChangeCarousel",
    value: function onSortingSelectChangeCarousel(event) {
      if (typeof ms_lms_courses_archive_filter.editor !== 'undefined') {
        return;
      }
      var sort_by = jQuery(event.currentTarget).val();
      this.filteringCarousel(sort_by);
    }
  }, {
    key: "onSortingButtonClick",
    value: function onSortingButtonClick(event) {
      event.preventDefault();
      if (typeof ms_lms_courses_archive_filter.editor !== 'undefined') {
        return;
      }
      jQuery(event.currentTarget).parent().siblings().find('.ms_lms_courses_archive__sorting_button').removeClass('active');
      jQuery(event.currentTarget).addClass('active');
      var sort_by = jQuery(event.currentTarget).data('id'),
        args = {};
      if (location.search) {
        args = this.getSearchArgs();
      }
      var current_page = 1;
      this.filtering(sort_by, args, current_page, false);
    }
  }, {
    key: "onSortingButtonClickGrid",
    value: function onSortingButtonClickGrid(event) {
      event.preventDefault();
      if (typeof ms_lms_courses_archive_filter.editor !== 'undefined') {
        return;
      }
      jQuery(event.currentTarget).parent().siblings().find('.ms_lms_courses_grid__sorting_button').removeClass('active');
      jQuery(event.currentTarget).addClass('active');
      var sort_by = jQuery(event.currentTarget).data('id');
      var current_page = 1;
      this.filteringGrid(sort_by, current_page, false);
    }
  }, {
    key: "onSortingButtonClickCarousel",
    value: function onSortingButtonClickCarousel(event) {
      event.preventDefault();
      if (typeof ms_lms_courses_archive_filter.editor !== 'undefined') {
        return;
      }
      jQuery(event.currentTarget).parent().siblings().find('.ms_lms_courses_carousel__sorting_button').removeClass('active');
      jQuery(event.currentTarget).addClass('active');
      var sort_by = jQuery(event.currentTarget).data('id');
      this.filteringCarousel(sort_by);
    }
  }, {
    key: "onFilterTitleClick",
    value: function onFilterTitleClick(event) {
      jQuery(event.currentTarget).toggleClass('active').next().slideToggle('medium', function () {
        if (jQuery(event.currentTarget).is(':visible')) {
          jQuery(event.currentTarget).css('display', 'flex');
        }
      });
    }
  }, {
    key: "onFilterToggleClick",
    value: function onFilterToggleClick(event) {
      event.preventDefault();
      this.elements.$filterForm.slideToggle('medium', function () {
        if (jQuery(event.currentTarget).is(':visible')) {
          jQuery(event.currentTarget).css('display', 'flex');
        }
      });
    }
  }, {
    key: "onShowMoreInstructorsClick",
    value: function onShowMoreInstructorsClick(event) {
      var categories = jQuery(event.currentTarget).closest('.ms_lms_courses_archive__filter_options_item_content').find('.ms_lms_courses_archive__filter_options_item_category');
      categories.slideDown('medium', function () {
        if (jQuery(event.currentTarget).is(':visible')) {
          jQuery(event.currentTarget).css('display', 'flex');
        }
      });
      jQuery(event.currentTarget).slideUp();
    }
  }, {
    key: "onFilterSubmitClick",
    value: function onFilterSubmitClick(event) {
      event.preventDefault();
      if (typeof ms_lms_courses_archive_filter.editor !== 'undefined') {
        return;
      }
      var sort_by = this.getSortArgs();
      var args = this.getFormArgs();
      var current_page = 1;
      this.filtering(sort_by, args, current_page, false);
    }
  }, {
    key: "onFilterResetClick",
    value: function onFilterResetClick(event) {
      event.preventDefault();
      if (typeof ms_lms_courses_archive_filter.editor !== 'undefined') {
        return;
      }
      history.replaceState({}, '', location.origin + location.pathname);
      location.reload();
    }
  }, {
    key: "onPaginationButtonClick",
    value: function onPaginationButtonClick(event) {
      event.preventDefault();
      if (typeof ms_lms_courses_archive_filter.editor !== 'undefined') {
        return;
      }
      var sort_by = this.getSortArgs(),
        args = {},
        current_page = parseInt(this.elements.$paginationItem.find('.current').text());
      if (location.search) {
        args = this.getSearchArgs();
      }
      if (!jQuery(event.currentTarget).hasClass('next') && !jQuery(event.currentTarget).hasClass('prev')) {
        jQuery(event.currentTarget).parent().siblings().find('.current').removeClass('current');
        jQuery(event.currentTarget).addClass('current');
        current_page = parseInt(jQuery(event.currentTarget).text());
      } else if (jQuery(event.currentTarget).hasClass('next')) {
        current_page = parseInt(jQuery(event.currentTarget).parent().siblings().find('.current').text());
        current_page++;
      } else if (jQuery(event.currentTarget).hasClass('prev')) {
        current_page = parseInt(jQuery(event.currentTarget).parent().siblings().find('.current').text());
        current_page--;
      }
      this.filtering(sort_by, args, current_page, false);
    }
  }, {
    key: "onPaginationButtonClickGrid",
    value: function onPaginationButtonClickGrid(event) {
      event.preventDefault();
      if (typeof ms_lms_courses_archive_filter.editor !== 'undefined') {
        return;
      }
      var sort_by = this.getSortArgsGrid(),
        current_page = parseInt(this.elements.$paginationItemGrid.find('.current').text());
      if (!jQuery(event.currentTarget).hasClass('next') && !jQuery(event.currentTarget).hasClass('prev')) {
        jQuery(event.currentTarget).parent().siblings().find('.current').removeClass('current');
        jQuery(event.currentTarget).addClass('current');
        current_page = parseInt(jQuery(event.currentTarget).text());
      } else if (jQuery(event.currentTarget).hasClass('next')) {
        current_page = parseInt(jQuery(event.currentTarget).parent().siblings().find('.current').text());
        current_page++;
      } else if (jQuery(event.currentTarget).hasClass('prev')) {
        current_page = parseInt(jQuery(event.currentTarget).parent().siblings().find('.current').text());
        current_page--;
      }
      this.filteringGrid(sort_by, current_page, false);
    }
  }, {
    key: "onLoadMoreButtonClick",
    value: function onLoadMoreButtonClick(event) {
      event.preventDefault();
      if (typeof ms_lms_courses_archive_filter.editor !== 'undefined') {
        return;
      }
      var sort_by = this.getSortArgs(),
        args = {};
      if (location.search) {
        args = this.getSearchArgs();
      }
      var offset = parseInt(jQuery(event.currentTarget).data('offset'));
      this.filtering(sort_by, args, 1, offset);
    }
  }, {
    key: "onLoadMoreButtonClickGrid",
    value: function onLoadMoreButtonClickGrid(event) {
      event.preventDefault();
      if (typeof ms_lms_courses_archive_filter.editor !== 'undefined') {
        return;
      }
      var sort_by = this.getSortArgsGrid();
      var offset = parseInt(jQuery(event.currentTarget).data('offset'));
      this.filteringGrid(sort_by, 1, offset);
    }
  }, {
    key: "getSortArgs",
    value: function getSortArgs() {
      if (this.elements.$sortingSelect.length) {
        return this.elements.$sortingSelect.val();
      } else if (this.elements.$sortingButton.length) {
        return this.elements.$sorting.find('.active').data('id');
      }
      return this.elements.$sortBy;
    }
  }, {
    key: "getSortArgsGrid",
    value: function getSortArgsGrid() {
      if (this.elements.$sortingSelectGrid.length) {
        return this.elements.$sortingSelectGrid.val();
      } else if (this.elements.$sortingButtonGrid.length) {
        return this.elements.$sortingGrid.find('.active').data('id');
      }
      return this.elements.$sortBy;
    }
  }, {
    key: "getFormArgs",
    value: function getFormArgs() {
      if (this.elements.$filter.length) {
        var values = this.elements.$filterForm.serializeArray();
        var args = {
          'terms': [],
          'meta_query': {
            'status': [],
            'level': [],
            'instructor': [],
            'price': [],
            'rating': 0
          }
        };
        values.forEach(function (element) {
          if (element['name'] === 'rating') {
            args['meta_query']['rating'] = element['value'];
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
  }, {
    key: "getSearchArgs",
    value: function getSearchArgs() {
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
  }, {
    key: "filtering",
    value: function filtering(sort_by, args, page, offset) {
      var _this = this,
        courses_container = _this.elements.$coursesContainer,
        pagination_container = _this.elements.$paginationContainer,
        scroll_container = _this.elements.$container,
        filter_button = _this.elements.$filterSubmit,
        filter_form = _this.elements.$filterForm;
      _this.urlAddParams(sort_by, args, page);
      jQuery.ajax({
        url: ms_lms_courses_archive_filter.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
          action: 'ms_lms_courses_archive_filter',
          card_template: _this.elements.$courseCardPresets,
          pagination_template: _this.elements.$paginationPresets,
          args: args,
          current_page: page,
          offset: offset,
          sort_by: sort_by,
          cards_to_show_choice: _this.elements.$cardsToShowChoice,
          cards_to_show: _this.elements.$cardsToShow,
          meta_slots: _this.elements.$metaSlots,
          card_data: _this.elements.$cardData,
          popup_data: _this.elements.$popupData,
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
            if (typeof _this.elements.$showFeaturedBlock !== 'undefined') {
              courses_container.empty();
            } else {
              courses_container.find('.ms_lms_courses_card_item:not(.featured)').remove();
            }
            if (window.matchMedia('(max-width: 1024px)').matches && filter_form.is(':visible')) {
              scroll_container = courses_container;
            }
            var containerTop = scroll_container.offset().top,
              window_top = jQuery(window).scrollTop(),
              window_height = jQuery(window).height(),
              window_bottom = window_top + window_height;
            if (!(containerTop >= jQuery(window).scrollTop() && containerTop + scroll_container.height() <= window_bottom)) {
              jQuery('html, body').animate({
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
        }
      });
    }
  }, {
    key: "filteringGrid",
    value: function filteringGrid(sort_by, page, offset) {
      var courses_container = this.elements.$coursesContainer,
        pagination_container = this.elements.$paginationContainerGrid,
        scroll_container = this.elements.$containerGrid;
      jQuery.ajax({
        url: ms_lms_courses_archive_filter.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
          action: 'ms_lms_courses_grid_sorting',
          card_template: this.elements.$courseCardPresets,
          pagination_template: this.elements.$paginationPresets,
          current_page: page,
          offset: offset,
          sort_by: sort_by,
          sort_by_cat: this.elements.$sortByCat,
          sort_by_default: this.elements.$sortBy,
          cards_to_show_choice: this.elements.$cardsToShowChoice,
          cards_to_show: this.elements.$cardsToShow,
          meta_slots: this.elements.$metaSlots,
          card_data: this.elements.$cardData,
          popup_data: this.elements.$popupData,
          nonce: ms_lms_courses_archive_filter.nonce
        },
        beforeSend: function beforeSend() {
          courses_container.addClass('loading');
        },
        success: function success(data) {
          if (!offset) {
            courses_container.find('.ms_lms_courses_card_item:not(.featured)').remove();
            var containerTop = scroll_container.offset().top,
              window_top = jQuery(window).scrollTop(),
              window_height = jQuery(window).height(),
              window_bottom = window_top + window_height;
            if (!(containerTop >= jQuery(window).scrollTop() && containerTop + scroll_container.height() <= window_bottom)) {
              jQuery('html, body').animate({
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
          courses_container.removeClass('loading');
        }
      });
    }
  }, {
    key: "filteringCarousel",
    value: function filteringCarousel(sort_by) {
      var _this = this;
      jQuery.ajax({
        url: ms_lms_courses_archive_filter.ajax_url,
        type: 'post',
        dataType: 'json',
        data: {
          action: 'ms_lms_courses_carousel_sorting',
          card_template: _this.elements.$courseCardPresets,
          sort_by: sort_by,
          sort_by_cat: _this.elements.$sortByCat,
          sort_by_default: _this.elements.$sortBy,
          cards_to_show_choice: _this.elements.$cardsToShowChoice,
          cards_to_show: _this.elements.$cardsToShow,
          meta_slots: _this.elements.$metaSlots,
          card_data: _this.elements.$cardData,
          popup_data: _this.elements.$popupData,
          widget_type: _this.elements.$widgetType,
          nonce: ms_lms_courses_archive_filter.nonce
        },
        beforeSend: function beforeSend() {
          _this.elements.$coursesContainer.addClass('loading');
          var widgetID = _this.elements.$sliderContainer.closest('.elementor-widget-ms_lms_courses').data('id');
          if (document.querySelector("[data-id=\"".concat(widgetID, "\"] .swiper-container")).swiper) {
            document.querySelector("[data-id=\"".concat(widgetID, "\"] .swiper-container")).swiper.destroy();
          }
        },
        success: function success(data) {
          _this.elements.$coursesContainer.find('.ms_lms_courses_card_item:not(.featured)').remove();
          if (data.cards) {
            _this.elements.$coursesContainer.append(data.cards);
            _this.CarouselInit();
          }
          _this.elements.$coursesContainer.removeClass('loading');
        }
      });
    }
  }, {
    key: "urlAddParams",
    value: function urlAddParams(sort_by, args, page) {
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
            if (key !== 'rating') {
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
  }, {
    key: "CarouselInit",
    value: function CarouselInit() {
      var _this = this,
        autoplayData = false,
        widgetID = _this.elements.$sliderContainer.closest('.elementor-widget-ms_lms_courses').data('id'),
        sliderContainer = document.querySelector("[data-id=\"".concat(widgetID, "\"] .ms_lms_courses_card_wrapper")),
        sliderButtonNext = document.querySelector("[data-id=\"".concat(widgetID, "\"] .ms_lms_courses_carousel__navigation_next")),
        sliderButtonPrev = document.querySelector("[data-id=\"".concat(widgetID, "\"] .ms_lms_courses_carousel__navigation_prev"));
      if (_this.elements.$carouselData['autoplay']) {
        autoplayData = {
          delay: 2000
        };
      }
      if (_this.elements.$containerCarousel.length !== 0 && typeof ms_lms_courses_archive_filter.editor === 'undefined') {
        var mySwiper = new Swiper(sliderContainer, {
          slidesPerView: _this.elements.$slidesOptions[_this.elements.$carouselData['slides_to_scroll']],
          watchSlidesProgress: true,
          breakpoints: {
            360: {
              slidesPerView: _this.elements.$slidesOptions[_this.elements.$carouselData['slides_to_scroll_mobile']]
            },
            768: {
              slidesPerView: _this.elements.$slidesOptions[_this.elements.$carouselData['slides_to_scroll_tablet']]
            },
            1025: {
              slidesPerView: _this.elements.$slidesOptions[_this.elements.$carouselData['slides_to_scroll']]
            }
          },
          loop: _this.elements.$carouselData['loop'],
          autoplay: autoplayData,
          navigation: {
            nextEl: sliderButtonNext,
            prevEl: sliderButtonPrev
          }
        });
      }
    }
  }]);
  return MsLmsCourses;
}(elementorModules.frontend.handlers.Base);
jQuery(window).on('elementor/frontend/init', function () {
  var addHandler = function addHandler($element) {
    elementorFrontend.elementsHandler.addHandler(MsLmsCourses, {
      $element: $element
    });
  };
  elementorFrontend.hooks.addAction('frontend/element_ready/ms_lms_courses.default', addHandler);
});