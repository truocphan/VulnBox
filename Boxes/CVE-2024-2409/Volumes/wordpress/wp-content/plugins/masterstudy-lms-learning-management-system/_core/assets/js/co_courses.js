"use strict";

/**
 * @var stm_lms_co_courses
 */

(function ($) {
  $(document).ready(function () {
    new Vue({
      el: '#stm_lms_instructor_co_courses',
      data: {
        courses: stm_lms_co_courses['posts'],
        pages: parseFloat(stm_lms_co_courses['pages']),
        page: 1,
        loading: false
      },
      methods: {
        getCourses: function getCourses() {
          var vm = this;
          var url = stm_lms_ajaxurl + '?action=stm_lms_get_co_courses&nonce=' + stm_lms_nonces['stm_lms_get_co_courses'] + '&page=' + vm.page;
          vm.loading = true;
          this.$http.get(url).then(function (response) {
            var res = response.body;
            vm.loading = false;
            vm.$set(vm, 'courses', res.posts);
          });
        }
      }
    });
  });
})(jQuery);