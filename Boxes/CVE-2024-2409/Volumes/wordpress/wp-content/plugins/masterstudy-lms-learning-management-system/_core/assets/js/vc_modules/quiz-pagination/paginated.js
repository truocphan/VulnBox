"use strict";

function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }
function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor); } }
function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }
function _toPropertyKey(arg) { var key = _toPrimitive(arg, "string"); return _typeof(key) === "symbol" ? key : String(key); }
function _toPrimitive(input, hint) { if (_typeof(input) !== "object" || input === null) return input; var prim = input[Symbol.toPrimitive]; if (prim !== undefined) { var res = prim.call(input, hint || "default"); if (_typeof(res) !== "object") return res; throw new TypeError("@@toPrimitive must return a primitive value."); } return (hint === "string" ? String : Number)(input); }
(function ($) {
  var PaginatedQuiz = /*#__PURE__*/function () {
    function PaginatedQuiz() {
      _classCallCheck(this, PaginatedQuiz);
      this.pagerSelector = 'single-pager';
      this.pages = $('.stm_lms_paginated_quiz_question');
      this.pagerWrapper = $('.stm_lms_paginated_quiz_pager');
      this.pager = '';
      this.active = 0;
      this.$currentQuestion = $('.stm_lms_paginated_quiz_number .current_q');
      this.createPager();
      this.activateQuestion();
      this.pagerClick();
      this.watchQuestion();
      if ($('.answers_shown').length) this.markAnswers();
    }
    _createClass(PaginatedQuiz, [{
      key: "activateQuestion",
      value: function activateQuestion() {
        this.pages.removeClass('active');
        this.pages.eq(this.active).addClass('active');
        this.pagerWrapper.find(".".concat(this.pagerSelector)).removeClass('active');
        this.pagerWrapper.find(".".concat(this.pagerSelector)).eq(this.active).addClass('active');
        this.$currentQuestion.text(this.active + 1);
        stm_lms_item_match_resize();
      }
    }, {
      key: "createPager",
      value: function createPager() {
        this.pagerWrapper.append("<div class=\"pager-prev\"><i class=\"fa fa-arrow-left\"></i></div>");
        for (var i = 0; i < this.pages.length; i++) {
          this.pagerWrapper.append("<div class=\"".concat(this.pagerSelector, "\" data-question=\"").concat(i, "\"><i class=\"fa fa-circle\" data-page=\"").concat(i + 1, "\"></i></div>"));
        }
        this.pagerWrapper.append("<div class=\"pager-next\"><i class=\"fa fa-arrow-right\"></i></div>");
      }
    }, {
      key: "pagerClick",
      value: function pagerClick() {
        var _this = this;
        this.pagerWrapper.on('click', '.pager-prev', function (e) {
          if (_this.active > 0) _this.active = _this.active - 1;
          _this.activateQuestion();
          _this.removeNextPrev();
        });
        this.pagerWrapper.on('click', ".".concat(this.pagerSelector), function (e) {
          _this.active = $(e.currentTarget).data('question');
          _this.activateQuestion();
          _this.removeNextPrev();
        });
        this.pagerWrapper.on('click', '.pager-next', function (e) {
          if (_this.active <= $(".".concat(_this.pagerSelector)).length - 2) _this.active = _this.active + 1;
          _this.activateQuestion();
          _this.removeNextPrev();
        });
        this.removeNextPrev();
      }
    }, {
      key: "watchQuestion",
      value: function watchQuestion() {
        var _this2 = this;
        $('.stm_lms_paginated_quiz_question').on('click mouseover', function () {
          _this2.watchInputs();
        });
      }
    }, {
      key: "removeNextPrev",
      value: function removeNextPrev() {
        var prevButton = this.pagerWrapper.find('.pager-prev');
        var nextButton = this.pagerWrapper.find('.pager-next');
        if (this.active <= 0) {
          prevButton.addClass('hidden');
          nextButton.removeClass('hidden');
        } else if (this.active >= $(".".concat(this.pagerSelector)).length - 1) {
          prevButton.removeClass('hidden');
          nextButton.addClass('hidden');
        } else {
          prevButton.removeClass('hidden');
          nextButton.removeClass('hidden');
        }
      }
    }, {
      key: "watchInputs",
      value: function watchInputs() {
        var _this3 = this;
        $('.stm_lms_paginated_quiz_question').each(function (key, question) {
          var $wrapper = $(question);
          var $pager = $('body').find('.single-pager');
          var hasValue = false;
          $wrapper.find("input").each(function (key, input) {
            var $input = $(input);
            switch ($input.attr('type')) {
              case "radio":
                if ($input.is(':checked')) hasValue = true;
                break;
              case "checkbox":
                if ($input.is(':checked')) hasValue = true;
                break;
              case "hidden":
                hasValue = false;
                break;
              default:
                if ($input.val()) hasValue = true;
            }
          });
          if (hasValue) {
            $pager.eq(key).addClass('hasAnswer');
          } else {
            $pager.eq(key).removeClass('hasAnswer');
          }
          _this3.enableSubmit();
        });
      }
    }, {
      key: "enableSubmit",
      value: function enableSubmit() {
        var $submit = $('.stm_lms_complete_lesson');
        var $pager = $('body').find('.single-pager');
        var enabled = true;
        $pager.each(function (key, pager) {
          if (!$(pager).hasClass('hasAnswer')) enabled = false;
        });
        if (enabled) {
          $submit.removeAttr('disabled');
        } else {
          $pager.attr('disabled', true);
        }
      }
    }, {
      key: "markAnswers",
      value: function markAnswers() {
        $('.stm_lms_paginated_quiz_question').each(function (key, question) {
          var $question = $(question);
          var $first_question = $question.find('.stm-lms-single_question:first');
          if ($first_question.hasClass('correct_answer_1')) {
            var $pager = $('body').find('.single-pager');
            $pager.eq(key).addClass('correctAnswer');
          }
          if ($first_question.hasClass('correct_answer_bank')) {
            var _$pager = $('body').find('.single-pager');
            var answers = {
              correct: 0,
              incorrect: 0
            };
            $question.find('.stm_lms_question_bank .stm-lms-single_question').each(function (bank_key, bank_question) {
              if ($(bank_question).hasClass('correct_answer_1')) {
                answers.correct++;
              } else {
                answers.incorrect++;
              }
            });
            console.log(answers);
            _$pager.eq(key).addClass('bank').html("<div class=\"correct_bank\">".concat(answers.correct, "</div><div class=\"incorrect_bank\">").concat(answers.incorrect, "</div>"));
          }
        });
      }
    }]);
    return PaginatedQuiz;
  }();
  $(document).ready(function () {
    new PaginatedQuiz();
  });
})(jQuery);