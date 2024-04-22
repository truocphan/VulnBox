"use strict";

(function ($) {
  $(document).ready(function () {
    /**
     * @var total_progress
     */

    var $popup = $('.stm_lms_finish_score_popup');
    $('.stm_lms_finish_score_popup__close').on('click', function () {
      $popup.removeClass('active');
    });
    if (total_progress.completed) {
      $popup.addClass('active');
    }
    if ($popup.hasClass('active')) stmLmsInitProgress();
  });
})(jQuery);
function stmLmsInitProgress() {
  new Vue({
    el: '#stm_lms_finish_score',
    data: function data() {
      return {
        course_id: total_progress.course_id,
        loading: true,
        stats: {}
      };
    },
    methods: {},
    mounted: function mounted() {
      var vm = this;
      vm.$http.get(stm_lms_ajaxurl + '?action=stm_lms_total_progress&course_id=' + this.course_id + '&nonce=' + stm_lms_nonces['stm_lms_total_progress']).then(function (r) {
        vm.$set(vm, 'stats', r.body);
        vm.$set(vm, 'loading', false);
      });
    }
  });
}