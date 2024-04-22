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
var MsLmsInstructorsCarousel = /*#__PURE__*/function (_elementorModules$fro) {
  _inherits(MsLmsInstructorsCarousel, _elementorModules$fro);
  var _super = _createSuper(MsLmsInstructorsCarousel);
  function MsLmsInstructorsCarousel() {
    _classCallCheck(this, MsLmsInstructorsCarousel);
    return _super.apply(this, arguments);
  }
  _createClass(MsLmsInstructorsCarousel, [{
    key: "getDefaultSettings",
    value: function getDefaultSettings() {
      return {
        selectors: {
          carousel: '.ms_lms_instructors_carousel__content'
        }
      };
    }
  }, {
    key: "getDefaultElements",
    value: function getDefaultElements() {
      var selectors = this.getSettings('selectors');
      var elementSettings = this.getElementSettings();
      return {
        $sliderContainer: this.$element.find(selectors.carousel),
        $slidesOptions: {
          '100%': 1,
          '50%': 2,
          '33.333333%': 3,
          '25%': 4,
          '20%': 5,
          '16.666666%': 6
        },
        $sliderData: {
          'slides_per_view': elementSettings['slides_per_view'],
          'slides_per_view_tablet': elementSettings['slides_per_view_tablet'],
          'slides_per_view_mobile': elementSettings['slides_per_view_mobile'],
          'autoplay': elementSettings['autoplay'],
          'loop': elementSettings['loop']
        }
      };
    }
  }, {
    key: "bindEvents",
    value: function bindEvents() {
      jQuery(document).ready(this.sliderInit.bind(this));
    }
  }, {
    key: "sliderInit",
    value: function sliderInit() {
      if (typeof ms_lms_instructors_carousel_mode.editor !== 'undefined') {
        return;
      }
      var _this = this,
        autoplayData = false,
        widgetID = _this.elements.$sliderContainer.closest('.elementor-widget-ms_lms_instructors_carousel').data('id'),
        sliderContainer = document.querySelector("[data-id=\"".concat(widgetID, "\"] .ms_lms_instructors_carousel__content")),
        sliderButtonNext = document.querySelector("[data-id=\"".concat(widgetID, "\"] .ms_lms_instructors_carousel__navigation_next")),
        sliderButtonPrev = document.querySelector("[data-id=\"".concat(widgetID, "\"] .ms_lms_instructors_carousel__navigation_prev"));
      if (_this.elements.$sliderData['autoplay']) {
        autoplayData = {
          delay: 2000
        };
      }
      if (_this.elements.$sliderContainer.length !== 0 && typeof edit_mode === 'undefined') {
        var mySwiper = new Swiper(sliderContainer, {
          slidesPerView: _this.elements.$slidesOptions[_this.elements.$sliderData['slides_per_view']],
          watchSlidesProgress: true,
          breakpoints: {
            360: {
              slidesPerView: _this.elements.$slidesOptions[_this.elements.$sliderData['slides_per_view_mobile']]
            },
            768: {
              slidesPerView: _this.elements.$slidesOptions[_this.elements.$sliderData['slides_per_view_tablet']]
            },
            1025: {
              slidesPerView: _this.elements.$slidesOptions[_this.elements.$sliderData['slides_per_view']]
            }
          },
          loop: _this.elements.$sliderData['loop'],
          autoplay: autoplayData,
          navigation: {
            nextEl: sliderButtonNext,
            prevEl: sliderButtonPrev
          }
        });
      }
    }
  }]);
  return MsLmsInstructorsCarousel;
}(elementorModules.frontend.handlers.Base);
jQuery(window).on('elementor/frontend/init', function () {
  var addHandler = function addHandler($element) {
    elementorFrontend.elementsHandler.addHandler(MsLmsInstructorsCarousel, {
      $element: $element
    });
  };
  elementorFrontend.hooks.addAction('frontend/element_ready/ms_lms_instructors_carousel.default', addHandler);
});