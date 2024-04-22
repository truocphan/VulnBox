"use strict";

(function ($) {
  $(document).ready(function () {
    new Vue({
      el: '#enrolled-quizzes',
      data: function data() {
        return {
          vue_loaded: true,
          loading: false,
          quizzes: [],
          offset: 0,
          total: false
        };
      },
      mounted: function mounted() {
        this.getQuizzes();
      },
      methods: {
        getQuizzes: function getQuizzes() {
          var vm = this;
          var url = stm_lms_ajaxurl + '?action=stm_lms_get_user_quizzes&nonce=' + stm_lms_nonces['stm_lms_get_user_quizzes'] + '&offset=' + vm.offset;
          vm.loading = true;
          this.$http.get(url).then(function (response) {
            if (response.body['posts']) {
              response.body['posts'].forEach(function (course) {
                vm.quizzes.push(course);
              });
            }
            vm.total = response.body['total'];
            vm.loading = false;
            vm.offset++;
          });
        }
      }
    });
  });
})(jQuery);