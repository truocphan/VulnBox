"use strict";

function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
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
var MsLmsCoursesSearchbox = /*#__PURE__*/function (_elementorModules$fro) {
  _inherits(MsLmsCoursesSearchbox, _elementorModules$fro);
  var _super = _createSuper(MsLmsCoursesSearchbox);
  function MsLmsCoursesSearchbox() {
    _classCallCheck(this, MsLmsCoursesSearchbox);
    return _super.apply(this, arguments);
  }
  _createClass(MsLmsCoursesSearchbox, [{
    key: "getDefaultSettings",
    value: function getDefaultSettings() {
      return {
        selectors: {
          container: '.ms_lms_course_search_box'
        }
      };
    }
  }, {
    key: "getDefaultElements",
    value: function getDefaultElements() {
      var selectors = this.getSettings('selectors');
      return {
        $container: this.$element.find(selectors.container)
      };
    }
  }, {
    key: "bindEvents",
    value: function bindEvents() {
      this.elements.$container.each(function () {
        var _this = jQuery(this);
        new Vue({
          el: _this[0],
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
      jQuery('.ms_lms_course_search_box__categories_dropdown_parent').hover(function () {
        var menu = jQuery('.ms_lms_course_search_box__categories_dropdown_childs_wrapper');
        var target = menu.find("[data-id='" + jQuery(this).data('id') + "']");
        if (target.length > 0) {
          target.siblings().removeClass('visible');
          menu.addClass('visible');
          target.addClass('visible');
        } else {
          menu.removeClass('visible');
          menu.find('.visible').removeClass('visible');
        }
      });
      jQuery('.ms_lms_course_search_box__categories_dropdown').mouseleave(function () {
        jQuery('.ms_lms_course_search_box__categories_dropdown_childs_wrapper').removeClass('visible');
        jQuery('.ms_lms_course_search_box__categories_dropdown_childs').removeClass('visible');
      });
      if (jQuery('.ms_lms_course_search_box__popup_button').length > 0) {
        var popup = jQuery('.ms_lms_course_search_box__popup');
        jQuery('.ms_lms_course_search_box__popup_button').click(function (e) {
          e.preventDefault();
          popup.addClass('visible');
          jQuery('body').addClass('ms_lms_course_search_box__popup_body');
        });
        jQuery(document).on('click', function (e) {
          if (popup.hasClass('visible')) {
            if (e.target === popup[0]) {
              popup.removeClass('visible');
              jQuery('body').removeClass('ms_lms_course_search_box__popup_body');
            }
          }
        });
        jQuery('.ms_lms_course_search_box__categories').hover(function () {
          if (popup.hasClass('visible') && popup.hasClass('without_wrapper')) {
            jQuery('.ms_lms_course_search_box__popup').addClass('ms_lms_searchbox_scroll');
          }
        }, function () {
          if (popup.hasClass('visible') && popup.hasClass('without_wrapper')) {
            jQuery('.ms_lms_course_search_box__popup').removeClass('ms_lms_searchbox_scroll');
          }
        });
      }
      if (jQuery('.ms_lms_course_search_box_compact').length > 0) {
        var compact_button = jQuery('.ms_lms_course_search_box__compact_button'),
          search_wrapper = jQuery('.ms_lms_course_search_box_compact_wrapper');
        compact_button.click(function (e) {
          if (jQuery(this).hasClass('opening')) {
            e.preventDefault();
            var width = jQuery('.search_button_compact').outerWidth();
            if (jQuery('.ms_lms_course_search_box__categories').length > 0) {
              width += jQuery('.ms_lms_course_search_box__categories').outerWidth();
            }
            jQuery(this).removeClass('opening');
            search_wrapper.width(width);
            setTimeout(function () {
              search_wrapper.removeClass('closed');
            }, 1000);
          }
        });
        jQuery(document).on('click', function (e) {
          if (!search_wrapper.hasClass('closed')) {
            if (!e.target.closest('.ms_lms_course_search_box_compact_wrapper')) {
              search_wrapper.addClass('closed').width(0);
              compact_button.addClass('opening');
            }
          }
        });
      }
      jQuery('.ms_lms_course_search_box__categories_dropdown_parent_wrapper .mobile_chevron').click(function () {
        jQuery(this).parent().parent().siblings().find('.ms_lms_course_search_box__categories_dropdown_mobile_childs').addClass('closed');
        jQuery(this).parent().parent().siblings().find('.mobile_chevron').removeClass('opened');
        jQuery(this).parent().next().toggleClass('closed');
        jQuery(this).toggleClass('opened');
      });
    }
  }]);
  return MsLmsCoursesSearchbox;
}(elementorModules.frontend.handlers.Base);
jQuery(window).on('elementor/frontend/init', function () {
  var addHandler = function addHandler($element) {
    elementorFrontend.elementsHandler.addHandler(MsLmsCoursesSearchbox, {
      $element: $element
    });
  };
  elementorFrontend.hooks.addAction('frontend/element_ready/ms_lms_courses_searchbox.default', addHandler);
});