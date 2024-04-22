"use strict";

(function ($) {
  $(document).ready(function () {
    $('.stm_lms_start_quiz, .btn-retake').on('click', function () {
      setTimeout(function () {
        init_sortable();
      }, 400);
    });
    function init_sortable() {
      $('.stm_lms_question_item_match').each(function () {
        var parent = $(this).closest('.stm-lms-single_question_item_match');
        $(".stm_lms_question_item_match__matches, .stm_lms_question_item_match__answer").sortable({
          connectWith: ".stm_lms_question_item_match__answer",
          appendTo: document.body,
          helper: "clone",
          start: function start(event, ui) {
            $(ui.item).addClass("dragging-item");
          },
          stop: function stop(event, ui) {
            $(ui.item).removeClass("dragging-item");
          },
          receive: function receive(event, ui) {
            var $parent = $(this);
            var $donor = $(ui.sender);
            if ($(this).children().length > 1) {
              /*Cancel All, and swap items*/
              $(ui.sender).sortable('cancel');
              if ($parent.hasClass('stm_lms_question_item_match__answer') && $donor.hasClass('stm_lms_question_item_match__answer')) {
                var $parent_slot = $parent.find('.stm_lms_question_item_match__match');
                var $donor_slot = $donor.find('.stm_lms_question_item_match__match');
                var parent_text = $parent_slot.text();
                var donor_text = $donor_slot.text();
                $parent_slot.text(donor_text);
                $donor_slot.text(parent_text);
              }
            }
            var items = [];
            var $answers = $parent.closest('.stm_lms_question_item_match__answers').find('.stm_lms_question_item_match__answer');
            if ($answers.length < 1) {
              $answers = $parent.closest('.stm_lms_question_item_match__questions').find('.stm_lms_question_item_match__single');
            }
            $answers.each(function () {
              var input_parent = $(this).closest('.stm_lms_question_item_match').find('.stm_lms_question_item_match__input');
              var slot = $(this).find('.stm_lms_question_item_match__match');
              var item = slot.length ? slot.text() : '';
              items.push(item);
              var item_match_val = "[stm_lms_item_match]" + items.join('[stm_lms_sep]');
              input_parent.val(item_match_val);
            });
          }
        });
      });
    }
  });
})(jQuery);