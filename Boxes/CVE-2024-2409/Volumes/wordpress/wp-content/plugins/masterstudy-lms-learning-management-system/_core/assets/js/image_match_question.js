"use strict";

(function ($) {
  $(document).ready(function () {
    $('.stm_lms_start_quiz, .btn-retake').on('click', function () {
      setTimeout(function () {
        init_sortable();
      }, 400);
    });
    function init_sortable() {
      $('.stm_lms_question_image_match__container').each(function () {
        if ($(this).find('img').length !== 0) {
          $(this).addClass('may_sort');
        } else {
          $(this).addClass('empty');
        }
      });
      $('.stm_lms_question_image_match').each(function () {
        var element_id = $(this).data('id');
        $('.container_' + element_id + '.may_sort, .answer_' + element_id).sortable({
          connectWith: ['.answer_' + element_id, '.container_' + element_id],
          appendTo: document.body,
          helper: 'clone',
          over: function stop(event, ui) {
            $(ui.placeholder).closest('.answer_' + element_id).removeClass('empty');
          },
          out: function start(event, ui) {
            var parent_item = $(ui.placeholder).closest('.answer_' + element_id);
            if (parent_item.find('.stm_lms_question_image_match__match').length < 2) {
              parent_item.addClass('empty');
            }
          },
          start: function start(event, ui) {
            $(ui.item).addClass('dragging-item');
            $(ui.item).parent().addClass('empty');
          },
          stop: function stop(event, ui) {
            $(ui.item).removeClass('dragging-item');
          },
          receive: function receive(event, ui) {
            var $parent = $(this);
            var $donor = $(ui.sender);
            $parent.closest('.answer_' + element_id).removeClass('empty');
            $parent.closest('.container_' + element_id).removeClass('empty');
            setTimeout(function () {
              if ($donor.html().length < 1) {
                $donor.closest('.answer_' + element_id).addClass('empty');
              }
            }, 0);
            if ($(this).children().length > 1) {
              /*Cancel All, and swap items*/
              $(ui.sender).sortable('cancel');
              if ($parent.hasClass('stm_lms_question_image_match__answer') && $donor.hasClass('stm_lms_question_image_match__answer')) {
                var $parent_slot = $parent.find('.stm_lms_question_image_match__match');
                var $donor_slot = $donor.find('.stm_lms_question_image_match__match');
                var parent_answer = '' + $parent_slot.data('answer');
                var parent_url = '' + $parent_slot.data('url');
                var donor_answer = '' + $donor_slot.data('answer');
                var donor_url = '' + $donor_slot.data('url');
                var parent_class = !donor_url.length ? 'image_box empty' : 'image_box';
                var parent_html = "<div class=\"image_match_answer\"><div class=\"".concat(parent_class, "\">");
                if (donor_url.length) {
                  parent_html += "<img src=\"".concat(donor_url, "\"/>");
                }
                parent_html += '</div>';
                if (donor_answer.length) {
                  parent_html += "<span>".concat(donor_answer, "</span>");
                }
                parent_html += '</div>';
                var donor_class = !parent_url.length ? 'image_box empty' : 'image_box';
                var donor_html = "<div class=\"image_match_answer\"><div class=\"".concat(donor_class, "\">");
                if (parent_url.length) {
                  donor_html += "<img src=\"".concat(parent_url, "\"/>");
                }
                donor_html += '</div>';
                if (parent_answer.length) {
                  donor_html += "<span>".concat(parent_answer, "</span>");
                }
                donor_html += '</div>';
                $parent_slot.data('answer', donor_answer);
                $parent_slot.data('url', donor_url);
                $donor_slot.data('answer', parent_answer);
                $donor_slot.data('url', parent_url);
                $parent_slot.html(parent_html);
                $donor_slot.html(donor_html);
              }
            }
            var items = [];
            var $answers;
            if ($(ui.sender).hasClass('stm_lms_question_image_match__container')) {
              $answers = $parent.closest('.stm_lms_question_image_match__questions').find('.answer_' + element_id);
            } else {
              $answers = $donor.closest('.stm_lms_question_image_match__questions').find('.answer_' + element_id);
            }
            $answers.each(function () {
              var input_parent = $(this).closest('.stm_lms_question_image_match__questions').find('.stm_lms_question_image_match__input');
              var slot = $(this).find('.stm_lms_question_image_match__match');
              var data_answer = '' + slot.data('answer');
              var data_url = '' + slot.data('url');
              var item = typeof data_answer !== 'undefined' && data_answer !== 'undefined' && data_answer.length ? data_answer : '';
              if (typeof data_url !== 'undefined' && data_url !== 'undefined' && data_url.length) {
                item += '|' + data_url;
              }
              items.push(item);
              var image_match_val = '[stm_lms_image_match]' + items.join('[stm_lms_sep]');
              input_parent.val(image_match_val);
            });
          }
        });
      });
    }
  });
})(jQuery);