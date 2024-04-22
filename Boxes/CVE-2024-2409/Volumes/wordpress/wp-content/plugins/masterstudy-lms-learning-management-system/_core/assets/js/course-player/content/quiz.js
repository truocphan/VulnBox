"use strict";

(function ($) {
  $(document).ready(function () {
    // h5p quiz integration
    if (typeof H5P !== 'undefined') {
      loadH5p();
    }
    function loadH5p() {
      H5P.externalDispatcher.on('xAPI', function (event) {
        if (typeof event.data.statement.result !== 'undefined') {
          var data = event.data.statement.result;
          data['action'] = 'stm_lms_add_h5p_result';
          data['sources'] = {
            post_id: quiz_data.course_id,
            item_id: quiz_data.quiz_id
          };
          $.ajax({
            type: 'POST',
            url: quiz_data.ajax_url + '?nonce=' + quiz_data.h5p_nonce,
            dataType: 'json',
            context: this,
            data: data,
            beforeSend: function beforeSend() {
              if (data.success === true) $('#stm-lms-lessons').addClass('loading');
            },
            complete: function complete(data) {
              data = data['responseJSON'];
              if (typeof data.completed !== 'undefined' && data.completed) {
                location.reload();
              } else {
                $('#stm-lms-lessons').removeClass('loading');
              }
            }
          });
        }
      });
    }

    // start quiz
    $("[data-id='start-quiz']").click(function (e) {
      e.preventDefault();
      $('.masterstudy-course-player-quiz__form').removeClass('masterstudy-course-player-quiz__form_hide');
      $('.masterstudy-course-player-navigation__submit-quiz').removeClass('masterstudy-course-player-navigation__submit-quiz_hide');
      $('.masterstudy-course-player-content__header').hide();
      $('.masterstudy-course-player-quiz__content').hide();
      $('.masterstudy-course-player-quiz__content-meta').hide();
      $('.masterstudy-course-player-quiz__start-quiz').hide();
      $('.masterstudy-course-player-header__navigation-quiz').addClass('masterstudy-course-player-header__navigation-quiz_show');
      $('.masterstudy-course-player-quiz__navigation-tabs').addClass('masterstudy-course-player-quiz__navigation-tabs_show');
      if ($('.masterstudy-course-player-question__content').find('.masterstudy-course-player-item-match').length > 0) {
        initializeItemMatch();
      }
      if ($('.masterstudy-course-player-question__content').find('.masterstudy-course-player-image-match').length > 0) {
        initializeImageMatch();
      }
      startQuiz();
    });

    // quiz alert
    $("[data-id='submit-quiz']").click(function (e) {
      e.preventDefault();
      $("[data-id='quiz_alert']").addClass('masterstudy-alert_open');
    });

    // submit quiz
    $("[data-id='quiz_alert']").find("[data-id='submit']").click(function (e) {
      e.preventDefault();
      submitQuiz();
    });
    function submitQuiz() {
      var data = {};
      var question_ids = [];
      $('.masterstudy-course-player-quiz__form').serializeArray().forEach(function (item) {
        /*if is array*/
        if (item.name.includes('[]')) {
          var key = item.name.replace('[]', '');
          if (typeof data[key] === 'undefined') {
            data[key] = [item.value];
          } else {
            data[key].push(item.value);
          }
        } else {
          if (item.name === 'question_ids') {
            question_ids = item.value.split(',');
          }
          data[item.name] = item.value;
        }
        if (question_ids.length > 0) {
          $.each(question_ids, function (index, key) {
            var bankQuestionsItems = $("[name*=\"questions_sequency[".concat(key, "]\"]"));
            if (bankQuestionsItems.length > 0) {
              $.each(bankQuestionsItems, function (i, item) {
                if (typeof data[$(item).val()] === 'undefined') {
                  data[$(item).val()] = [''];
                }
              });
              delete data[key];
            } else {
              if (typeof data[key] === 'undefined') {
                data[key] = [''];
              }
            }
          });
        }
      });
      $.ajax({
        type: 'POST',
        url: quiz_data.ajax_url + '?nonce=' + quiz_data.submit_nonce,
        dataType: 'json',
        context: this,
        data: data,
        beforeSend: function beforeSend() {
          $("[data-id='quiz_alert']").removeClass('masterstudy-alert_open');
          $("[data-id='submit-quiz']").addClass('masterstudy-button_loading');
        },
        success: function success(data) {
          if (quiz_data.is_single_quiz) {
            var date = new Date();
            date.setTime(date.getTime() + 60 * 60 * 1000);
            document.cookie = "quiz_user_answer_id=".concat(data.user_answer_id, ";expires=").concat(date.toUTCString(), ";path=/");
            var currentUrl = currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('show_answers', data.user_answer_id);
            currentUrl.searchParams.set('progress', data.progress);
            window.location.href = currentUrl;
          } else {
            location.reload();
          }
        }
      });
    }

    //cancel submit
    $("[data-id='quiz_alert']").find("[data-id='cancel']").click(function (e) {
      e.preventDefault();
      $("[data-id='quiz_alert']").removeClass('masterstudy-alert_open');
    });
    $("[data-id='quiz_alert']").find('.masterstudy-alert__header-close').click(function (e) {
      e.preventDefault();
      $("[data-id='quiz_alert']").removeClass('masterstudy-alert_open');
    });

    // retake quiz
    $('.masterstudy-course-player-quiz__result-retake .masterstudy-button').click(function () {
      var container = $('.masterstudy-course-player-question__content');
      var quizForm = $('.masterstudy-course-player-quiz__form');
      var submitQuiz = $('.masterstudy-course-player-navigation__submit-quiz');
      var answerInputs = container.find('.masterstudy-course-player-answer input');
      var answerCheckboxes = container.find('.masterstudy-course-player-answer__checkbox');
      var answerRadios = container.find('.masterstudy-course-player-answer__radio');
      var wrongStatus = container.find('.masterstudy-course-player-answer__status-wrong');
      var correctStatus = container.find('.masterstudy-course-player-answer__status-correct');
      var itemMatchAnswers = container.find('.masterstudy-course-player-item-match');
      var imageMatchAnswers = container.find('.masterstudy-course-player-image-match');
      var fillTheGap = container.find('.masterstudy-course-player-fill-the-gap');
      var keywords = container.find('.masterstudy-course-player-quiz-keywords');

      // hide unnecessary blocks
      quizForm.removeClass('masterstudy-course-player-quiz__form_hide');
      submitQuiz.removeClass('masterstudy-course-player-navigation__submit-quiz_hide');

      // reset single, multi choice answers & true|false answers
      container.find('.masterstudy-course-player-answer').removeClass('masterstudy-course-player-answer_show-answers masterstudy-course-player-answer_correct masterstudy-course-player-answer_wrong');
      answerInputs.prop('checked', false);
      answerCheckboxes.removeClass('masterstudy-course-player-answer__checkbox_checked');
      answerRadios.removeClass('masterstudy-course-player-answer__radio_checked');
      wrongStatus.hide();
      correctStatus.hide();

      //reset pagination indicators
      $('.masterstudy-course-player-quiz').removeClass('masterstudy-course-player-quiz_show-answers');
      $('.masterstudy-pagination__item-indicator').removeClass('masterstudy-pagination__item-indicator_done');
      $('.masterstudy-course-player-quiz__navigation-tabs').addClass('masterstudy-course-player-quiz__navigation-tabs_show');

      // reset Item Match answers
      if (itemMatchAnswers.length > 0) {
        itemMatchAnswers.removeClass('masterstudy-course-player-item-match_not-drag');
        itemMatchAnswers.find('.masterstudy-course-player-item-match__question-answer .masterstudy-course-player-item-match__answer-item').remove();
        itemMatchAnswers.find('.masterstudy-course-player-item-match__question-answer-text').removeClass('masterstudy-course-player-item-match__question-answer-text_hide');
        itemMatchAnswers.find('.masterstudy-course-player-item-match__answer').removeClass('masterstudy-course-player-item-match__answer_hide');
        itemMatchAnswers.find('.masterstudy-course-player-item-match__question').removeClass('masterstudy-course-player-item-match__question_correct masterstudy-course-player-item-match__question_wrong masterstudy-course-player-item-match__question_full');
        itemMatchAnswers.find('.masterstudy-course-player-item-match__input').val('').attr('value', '');
        initializeItemMatch();
      }

      // reset Image Match answers
      if (imageMatchAnswers.length > 0) {
        imageMatchAnswers.removeClass('masterstudy-course-player-image-match_not-drag');
        imageMatchAnswers.find('.masterstudy-course-player-image-match__question-answer-wrongly').remove();
        imageMatchAnswers.find('.masterstudy-course-player-image-match__question-answer .masterstudy-course-player-image-match__answer-item').remove();
        imageMatchAnswers.find('.masterstudy-course-player-image-match__question-answer-drag-text').removeClass('masterstudy-course-player-image-match__question-answer-drag-text_hide');
        imageMatchAnswers.find('.masterstudy-course-player-image-match__answer').removeClass('masterstudy-course-player-image-match__answer_hide');
        imageMatchAnswers.find('.masterstudy-course-player-image-match__question').removeClass('masterstudy-course-player-image-match__question_correct masterstudy-course-player-image-match__question_wrong masterstudy-course-player-image-match__question_full');
        imageMatchAnswers.find('.masterstudy-course-player-image-match__question-status').addClass('masterstudy-course-player-image-match__question-status_hide');
        imageMatchAnswers.find('.masterstudy-course-player-image-match__input').val('').attr('value', '');
        initializeImageMatch();
      }

      // reset fill the gap answers
      if (fillTheGap.length > 0) {
        fillTheGap.find('.masterstudy-course-player-fill-the-gap__questions').removeClass('hidden');
        fillTheGap.find('.masterstudy-course-player-fill-the-gap__answers').remove();
      }

      // reset keywords answers
      if (keywords.length > 0) {
        keywords.find('.masterstudy-course-player-quiz-keywords__questions').removeClass('hidden');
        keywords.find('.masterstudy-course-player-quiz-keywords__user_answers').remove();
      }

      // hide unnecessary blocks
      $('.masterstudy-course-player-content__header').hide();
      $('.masterstudy-course-player-quiz__content').hide();
      $('.masterstudy-course-player-quiz__content-meta').hide();
      $('.masterstudy-course-player-quiz__result-container').hide();
      $('.masterstudy-course-player-answer__hint').hide();
      $('.masterstudy-course-player-header__navigation-quiz').addClass('masterstudy-course-player-header__navigation-quiz_show');
      startQuiz();
    });
    function startQuiz() {
      if (!quiz_data.duration > 0) {
        return;
      }
      $.ajax({
        url: quiz_data.ajax_url,
        dataType: 'json',
        context: this,
        data: {
          'quiz_id': quiz_data.quiz_id,
          'action': 'stm_lms_start_quiz',
          'nonce': quiz_data.start_nonce,
          'source': quiz_data.course_id
        },
        success: function success(data) {
          if ($('.masterstudy-course-player-quiz-timer').length > 0) {
            countTo(parseInt(data) * 1000);
            $('.masterstudy-course-player-quiz-timer').addClass('masterstudy-course-player-quiz-timer_started');
          }
        }
      });
    }

    // scroll to question
    $('.masterstudy-course-player-quiz__navigation-tabs .masterstudy-tabs-pagination__item-block').click(function () {
      var questionId = $(this).data('id');
      document.querySelector(".masterstudy-course-player-quiz__form [data-number-question=\"".concat(questionId, "\"]")).scrollIntoView({
        behavior: 'smooth'
      });
    });

    // quiz timer countdown
    var countInterval,
      timeOut = false;
    function countTo(countDownDate) {
      clearInterval(countInterval);
      countInterval = setInterval(function () {
        var now = new Date().getTime();
        var distance = countDownDate - now;
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor(distance % (1000 * 60 * 60 * 24) / (1000 * 60 * 60));
        var minutes = Math.floor(distance % (1000 * 60 * 60) / (1000 * 60));
        var seconds = Math.floor(distance % (1000 * 60) / 1000);
        if (hours < 10) hours = '0' + hours;
        if (minutes < 10) minutes = '0' + minutes;
        if (seconds < 10) seconds = '0' + seconds;
        if (hours === '00' && minutes < 60) {
          $('.masterstudy-course-player-quiz-timer__minutes').text(minutes);
          $('.masterstudy-course-player-quiz-timer__seconds').text(seconds);
          $('.masterstudy-course-player-quiz-timer__separator[data-id="minutes"]').addClass('masterstudy-course-player-quiz-timer__separator_show');
        } else if (days < 1) {
          $('.masterstudy-course-player-quiz-timer__hours').text(hours);
          $('.masterstudy-course-player-quiz-timer__minutes').text(minutes);
          $('.masterstudy-course-player-quiz-timer__seconds').text(seconds);
          $('.masterstudy-course-player-quiz-timer__separator').addClass('masterstudy-course-player-quiz-timer__separator_show');
        } else {
          var daysText = $('.masterstudy-course-player-quiz-timer').attr('data-text-days');
          $('.masterstudy-course-player-quiz-timer__days').text(days + ' ' + daysText);
        }
        if (!timeOut && distance < 1001) {
          clearInterval(countInterval);
          timeOut = true;
          quiz_data.prevent_submit = 1;
          submitQuiz();
        }
      }, 1000);
    }
    function initializeItemMatch() {
      $('.masterstudy-course-player-item-match:not(.masterstudy-course-player-item-match_not-drag)').each(function (index) {
        var questionClass = 'item_drag_' + index;
        $(this).find('.masterstudy-course-player-item-match__answer, .masterstudy-course-player-item-match__question-answer').sortable({
          connectWith: '.' + questionClass + '.masterstudy-course-player-item-match__question-answer',
          appendTo: '.' + questionClass + '.masterstudy-course-player-item-match__answer',
          helper: 'clone',
          start: function start(event, ui) {
            $(ui.helper).css('cursor', 'grabbing');
          },
          stop: function stop(event, ui) {
            $(ui.helper).css('cursor', 'grab');
          },
          over: function over(event, ui) {
            var parent = $(this);
            if (parent.hasClass('masterstudy-course-player-item-match__question-answer')) {
              parent.addClass('masterstudy-course-player-item-match__question-answer_highlight');
            }
          },
          out: function out(event, ui) {
            var parent = $(this);
            if (parent.hasClass('masterstudy-course-player-item-match__question-answer')) {
              parent.removeClass('masterstudy-course-player-item-match__question-answer_highlight');
            }
          },
          remove: function remove(event, ui) {
            var parent = $(this);
            if (parent.hasClass('masterstudy-course-player-item-match__question-answer')) {
              parent.closest('.masterstudy-course-player-item-match__question-answer-wrapper').find('.masterstudy-course-player-item-match__question-answer-text').show();
              parent.closest('.masterstudy-course-player-item-match__question').removeClass('masterstudy-course-player-item-match__question_full');
            }
          },
          receive: function receive(event, ui) {
            var parent = $(this);
            var donor = $(ui.sender);
            if (parent.hasClass('masterstudy-course-player-item-match__question-answer')) {
              parent.closest('.masterstudy-course-player-item-match__question-answer-wrapper').find('.masterstudy-course-player-item-match__question-answer-text').hide();
              parent.closest('.masterstudy-course-player-item-match__question').addClass('masterstudy-course-player-item-match__question_full');
            }
            if (parent.children().length > 1) {
              /*Swap items*/
              $(ui.sender).sortable('cancel');
              if (parent.hasClass('masterstudy-course-player-item-match__question-answer') && donor.hasClass('masterstudy-course-player-item-match__question-answer')) {
                var parent_content = parent.find('.masterstudy-course-player-item-match__answer-item-content');
                var donor_content = donor.find('.masterstudy-course-player-item-match__answer-item-content');
                var parent_text = parent_content.text();
                var donor_text = donor_content.text();
                parent_content.text(donor_text);
                donor_content.text(parent_text);
                parent.closest('.masterstudy-course-player-item-match__question-answer-wrapper').find('.masterstudy-course-player-item-match__question-answer-text').hide();
                donor.closest('.masterstudy-course-player-item-match__question-answer-wrapper').find('.masterstudy-course-player-item-match__question-answer-text').hide();
                parent.closest('.masterstudy-course-player-item-match__question').addClass('masterstudy-course-player-item-match__question_full');
                donor.closest('.masterstudy-course-player-item-match__question').addClass('masterstudy-course-player-item-match__question_full');
              }
            }
            var items = [];
            var answers = parent.closest('.masterstudy-course-player-item-match').find('.masterstudy-course-player-item-match__question');
            if (answers.length > 0) {
              answers.each(function () {
                var input_parent = $(this).closest('.masterstudy-course-player-item-match').find('.masterstudy-course-player-item-match__input');
                var slot = $(this).find('.masterstudy-course-player-item-match__answer-item-content');
                var item = slot.length ? slot.text().trim() : '';
                items.push(item);
                var item_match_val = '[stm_lms_item_match]' + items.join('[stm_lms_sep]');
                input_parent.val(item_match_val);
              });
            }
          }
        }).addClass(questionClass);
      });
    }
    function initializeImageMatch() {
      $('.masterstudy-course-player-image-match:not(.masterstudy-course-player-image-match_not-drag)').each(function (index) {
        var questionClass = 'image_drag_' + index;
        $(this).find('.masterstudy-course-player-image-match__answer, .masterstudy-course-player-image-match__question-answer').sortable({
          connectWith: '.' + questionClass + '.masterstudy-course-player-image-match__question-answer',
          appendTo: '.' + questionClass + '.masterstudy-course-player-image-match__answer',
          helper: 'clone',
          start: function start(event, ui) {
            var isQuestionAnswer = $(ui.item).parent().hasClass('masterstudy-course-player-image-match__question-answer');
            var isGridStyle = $(ui.helper).closest('.masterstudy-course-player-image-match').hasClass('masterstudy-course-player-image-match_style-grid');
            var isSmallScreen = window.matchMedia('(max-width: 576px)').matches;
            var height;
            $(ui.helper).css('cursor', 'grabbing');
            if (isQuestionAnswer) {
              if (isGridStyle) {
                height = isSmallScreen ? '105px' : '177px';
              } else {
                height = isSmallScreen ? '105px' : '280px';
              }
              $(ui.helper).find('img').css('height', height);
            }
          },
          stop: function stop(event, ui) {
            $(ui.helper).css('cursor', 'grab');
            $(ui.helper).css('width', '100%');
          },
          over: function over(event, ui) {
            var parent = $(this);
            if (parent.hasClass('masterstudy-course-player-image-match__question-answer')) {
              parent.addClass('masterstudy-course-player-image-match__question-answer_highlight');
            }
          },
          out: function out(event, ui) {
            var parent = $(this);
            if (parent.hasClass('masterstudy-course-player-image-match__question-answer')) {
              parent.removeClass('masterstudy-course-player-image-match__question-answer_highlight');
            }
          },
          remove: function remove(event, ui) {
            var parent = $(this);
            if (parent.hasClass('masterstudy-course-player-image-match__question-answer')) {
              parent.closest('.masterstudy-course-player-image-match__question-answer-wrapper').find('.masterstudy-course-player-image-match__question-answer-drag-text').show();
              parent.closest('.masterstudy-course-player-image-match__question').removeClass('masterstudy-course-player-image-match__question_full');
            }
          },
          receive: function receive(event, ui) {
            var parent = $(this);
            var donor = $(ui.sender);
            if (parent.hasClass('masterstudy-course-player-image-match__question-answer')) {
              parent.closest('.masterstudy-course-player-image-match__question-answer-wrapper').find('.masterstudy-course-player-image-match__question-answer-drag-text').hide();
              parent.closest('.masterstudy-course-player-image-match__question').addClass('masterstudy-course-player-image-match__question_full');
            }
            if (parent.children().length > 1) {
              /*Swap items*/
              $(ui.sender).sortable('cancel');
              if (parent.hasClass('masterstudy-course-player-image-match__question-answer') && donor.hasClass('masterstudy-course-player-image-match__question-answer')) {
                var parent_img_container = parent.find('.masterstudy-course-player-image-match__answer-item-content img');
                var donor_img_container = donor.find('.masterstudy-course-player-image-match__answer-item-content img');
                var donor_text_container = donor.find('.masterstudy-course-player-image-match__answer-item-text');
                var parent_text_container = parent.find('.masterstudy-course-player-image-match__answer-item-text');
                var parent_img = parent_img_container.attr('src');
                var donor_img = donor_img_container.attr('src');
                var donor_text = donor_text_container.text();
                var parent_text = parent_text_container.text();
                var empty_text_donor = donor.find('.masterstudy-course-player-image-match__answer-item-container_hide').length > 0;
                var empty_text_parent = parent.find('.masterstudy-course-player-image-match__answer-item-container_hide').length > 0;
                parent_img_container.attr('src', donor_img);
                donor_img_container.attr('src', parent_img);
                donor_text_container.text(parent_text);
                parent_text_container.text(donor_text);
                if (empty_text_donor && !empty_text_parent) {
                  donor.find('.masterstudy-course-player-image-match__answer-item-container').removeClass('masterstudy-course-player-image-match__answer-item-container_hide');
                  parent.find('.masterstudy-course-player-image-match__answer-item-container').addClass('masterstudy-course-player-image-match__answer-item-container_hide');
                } else if (empty_text_parent && !empty_text_donor) {
                  donor.find('.masterstudy-course-player-image-match__answer-item-container').addClass('masterstudy-course-player-image-match__answer-item-container_hide');
                  parent.find('.masterstudy-course-player-image-match__answer-item-container').removeClass('masterstudy-course-player-image-match__answer-item-container_hide');
                }
                parent.closest('.masterstudy-course-player-image-match__question-answer-wrapper').find('.masterstudy-course-player-image-match__question-answer-drag-text').hide();
                donor.closest('.masterstudy-course-player-image-match__question-answer-wrapper').find('.masterstudy-course-player-image-match__question-answer-drag-text').hide();
                parent.closest('.masterstudy-course-player-image-match__question').addClass('masterstudy-course-player-image-match__question_full');
                donor.closest('.masterstudy-course-player-image-match__question').addClass('masterstudy-course-player-image-match__question_full');
              }
            }
            var items = [];
            var answers = parent.closest('.masterstudy-course-player-image-match').find('.masterstudy-course-player-image-match__question');
            if (answers.length > 0) {
              answers.each(function () {
                var input_parent = $(this).closest('.masterstudy-course-player-image-match').find('.masterstudy-course-player-image-match__input');
                var slot = $(this).find('.masterstudy-course-player-image-match__answer-item-content');
                var item = slot.length ? slot.find('.masterstudy-course-player-image-match__answer-item-text').text().trim() : '';
                if ($(this).find('.masterstudy-course-player-image-match__answer-item-content img').length > 0) {
                  item += '|' + $(this).find('.masterstudy-course-player-image-match__answer-item-content img').attr('src');
                }
                items.push(item);
                var image_match_val = '[stm_lms_image_match]' + items.join('[stm_lms_sep]');
                input_parent.val(image_match_val);
              });
            }
          }
        }).addClass(questionClass);
      });
    }
  });
})(jQuery);