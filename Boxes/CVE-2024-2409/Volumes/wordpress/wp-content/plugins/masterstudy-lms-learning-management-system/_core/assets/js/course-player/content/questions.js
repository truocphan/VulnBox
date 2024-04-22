"use strict";

(function ($) {
  $(document).ready(function () {
    if (typeof MasterstudyAudioPlayer !== 'undefined') {
      MasterstudyAudioPlayer.init({
        selector: '.masterstudy-audio-player',
        showDeleteButton: false
      });
    }
    // show question when paginated
    $('.masterstudy-course-player-quiz__pagination .masterstudy-pagination__item-block').click(function () {
      $(".masterstudy-course-player-quiz__form [data-number-question=\"".concat($(this).data('id'), "\"]")).each(function () {
        toggleQuestions($(this));
      });
    });
    $('.masterstudy-course-player-quiz__pagination .masterstudy-pagination__button-next').click(function () {
      var currentPage = $(this).parent().find('.masterstudy-pagination__item_current .masterstudy-pagination__item-block').data('id');
      $(".masterstudy-course-player-quiz__form [data-number-question=\"".concat(currentPage + 1, "\"]")).each(function () {
        toggleQuestions($(this));
      });
    });
    $('.masterstudy-course-player-quiz__pagination .masterstudy-pagination__button-prev').click(function () {
      var currentPage = $(this).parent().find('.masterstudy-pagination__item_current .masterstudy-pagination__item-block').data('id');
      $(".masterstudy-course-player-quiz__form [data-number-question=\"".concat(currentPage - 1, "\"]")).each(function () {
        toggleQuestions($(this));
      });
    });
    function toggleQuestions(question) {
      var dataId = question.attr('data-number-question');
      var parent_bank = question.parent().closest('.masterstudy-course-player-question');
      var other_questions = question.closest('.masterstudy-course-player-quiz__questions').find('.masterstudy-course-player-question').not('.masterstudy-course-player-question_question-bank').not("[data-number-question=\"".concat(dataId, "\"]"));
      if (parent_bank.hasClass('masterstudy-course-player-question_question-bank')) {
        parent_bank.removeClass('masterstudy-course-player-question_hide');
      }
      question.removeClass('masterstudy-course-player-question_hide');
      other_questions.addClass('masterstudy-course-player-question_hide');
    }

    // make indicator in pagination 'done'
    if ($('.masterstudy-course-player-quiz__pagination').length > 0) {
      indicateAnswers();
      $('.masterstudy-course-player-question:not(.masterstudy-course-player-question_question-bank)').on('click mouseleave touchend', function () {
        if (!$('.masterstudy-course-player-quiz').hasClass('masterstudy-course-player-quiz_show-answers')) {
          var hasValue = false;
          var pager = $('.masterstudy-course-player-quiz__pagination .masterstudy-pagination__item');
          $(this).find('input').each(function (index, input) {
            switch ($(input).prop('type')) {
              case 'radio':
              case 'checkbox':
                if ($(input).prop('checked')) {
                  hasValue = true;
                }
                break;
              case 'hidden':
                hasValue = false;
                break;
              default:
                if ($(input).val()) {
                  hasValue = true;
                }
            }
            if (hasValue) {
              return false;
            }
          });
          var value = $(this).data('number-question') - 1;
          if (hasValue) {
            pager.eq(value).find('.masterstudy-pagination__item-indicator').addClass('masterstudy-pagination__item-indicator_done');
          } else {
            pager.eq(value).find('.masterstudy-pagination__item-indicator').removeClass('masterstudy-pagination__item-indicator_done');
          }
        }
      });
    }
    function indicateAnswers() {
      var pager = $('.masterstudy-course-player-quiz__pagination .masterstudy-pagination__item');
      $('.masterstudy-course-player-question:not(.masterstudy-course-player-question_question-bank)').each(function () {
        if ($(this).hasClass('masterstudy-course-player-question_correct') || $(this).hasClass('masterstudy-course-player-question_wrong')) {
          var index = $(this).data('number-question') - 1;
          pager.eq(index).find('.masterstudy-pagination__item-indicator').addClass('masterstudy-pagination__item-indicator_done');
        }
      });
    }

    // submit question
    $('.masterstudy-course-player-answer').click(function (e) {
      if (!$('.masterstudy-course-player-quiz').hasClass('masterstudy-course-player-quiz_show-answers')) {
        var input = $(this).find('input');
        if (input.is(':radio')) {
          input.prop('checked', true);
          $(this).siblings('.masterstudy-course-player-answer').find('input:radio').prop('checked', false);
          $(this).addClass('masterstudy-course-player-answer_checked');
          $(this).find('.masterstudy-course-player-answer__radio').addClass('masterstudy-course-player-answer__radio_checked');
          $(this).siblings('.masterstudy-course-player-answer').removeClass('masterstudy-course-player-answer_checked');
          $(this).siblings('.masterstudy-course-player-answer').find('.masterstudy-course-player-answer__radio').removeClass('masterstudy-course-player-answer__radio_checked');
        } else if (input.is(':checkbox')) {
          if (!input.prop('checked')) {
            input.prop('checked', true);
            $(this).addClass('masterstudy-course-player-answer_checked');
            $(this).find('.masterstudy-course-player-answer__checkbox').addClass('masterstudy-course-player-answer__checkbox_checked');
          } else {
            input.prop('checked', false);
            $(this).removeClass('masterstudy-course-player-answer_checked');
            $(this).find('.masterstudy-course-player-answer__checkbox').removeClass('masterstudy-course-player-answer__checkbox_checked');
          }
        }
      }
    });

    // Quiz keywords
    var timeout;
    function handleKeywordInput() {
      clearTimeout(timeout);
      var $input = $(this);
      var $parent = $input.closest('.masterstudy-course-player-quiz-keywords');
      var answers = window[$parent.attr('data-quiz-keywords')];
      var userAnswer = $input.val().toLowerCase();
      $parent.find('.masterstudy-course-player-quiz-keywords__input').removeAttr('required');
      timeout = setTimeout(function () {
        answers.forEach(function (answer, answerIndex) {
          if (userAnswer.includes(answer)) {
            $input.val('').focus();
            var $insertTo = $parent.find('.masterstudy-course-player-quiz-keywords__answer_' + answerIndex + ' .masterstudy-course-player-quiz-keywords__value');
            var $flying = $parent.find('.masterstudy-course-player-quiz-keywords__flying-word').text(answer);
            var childPos = $insertTo.offset();
            var parentPos = $insertTo.closest('.masterstudy-course-player-quiz-keywords').offset();
            $flying.addClass('visible').css({
              top: childPos.top - parentPos.top + 0,
              left: childPos.left - parentPos.left + 0
            });
            setTimeout(function () {
              $insertTo.text(answer);
              answers[answerIndex] = '(^-^*)/';
              $flying.text('').css({
                top: '20px',
                left: '14px'
              }).removeClass('visible');
              addAnswer();
            }, 500);
          }
        });
      }, 300);
    }
    $('.masterstudy-course-player-quiz-keywords__keyword-to-fill').on('keyup', handleKeywordInput);
    function addAnswer() {
      $('.masterstudy-course-player-quiz-keywords').each(function () {
        var answers = $(this).find('.masterstudy-course-player-quiz-keywords__answer .masterstudy-course-player-quiz-keywords__value').map(function () {
          return $(this).text().trim();
        }).get().filter(Boolean);
        var $input = $(this).find('.masterstudy-course-player-quiz-keywords__input');
        var keywords_val = "[stm_lms_keywords]" + answers.join('[stm_lms_sep]');
        if (answers.length) {
          $input.val(keywords_val);
        }
      });
    }
    var videoPlaying = null;
    $(".masterstudy-pagination__item-block, .masterstudy-tabs-pagination__item-block").click(function () {
      var videos = document.getElementsByTagName("video");
      for (var i = 0; i < videos.length; i++) {
        videos[i].pause();
      }
    });
    var onPlay = function onPlay() {
      if (videoPlaying && videoPlaying != this) {
        videoPlaying.pause();
      }
      videoPlaying = this;
    };
    var videos = document.getElementsByTagName("video");
    for (var i = 0; i < videos.length; i++) {
      videos[i].addEventListener("play", onPlay);
    }
  });
})(jQuery);