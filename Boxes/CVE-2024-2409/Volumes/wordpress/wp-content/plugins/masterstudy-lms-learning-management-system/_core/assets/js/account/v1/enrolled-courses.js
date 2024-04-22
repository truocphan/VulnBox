"use strict";

(function ($) {
  $(document).ready(function () {
    new Vue({
      el: '#enrolled-courses',
      data: function data() {
        return {
          vue_loaded: true,
          loading: false,
          courses: [],
          offset: 0,
          total: false,
          sort: 'date_low'
        };
      },
      mounted: function mounted() {
        this.getCourses();
        this.sortToggle();
      },
      methods: {
        getCourses: function getCourses() {
          console.log(this.sort);
          var vm = this;
          var url = stm_lms_ajaxurl + '?action=stm_lms_get_user_courses&offset=' + vm.offset + '&nonce=' + stm_lms_nonces['stm_lms_get_user_courses'];
          url += "&sort=".concat(vm.sort);
          vm.loading = true;
          this.$http.get(url).then(function (response) {
            if (response.body['posts']) {
              response.body['posts'].forEach(function (course) {
                vm.courses.push(course);
              });
            }
            vm.total = response.body['total'];
            vm.loading = false;
            vm.offset++;
            Vue.nextTick(function () {
              stmLmsStartTimers();
            });
          });
        },
        sortToggle: function sortToggle() {
          var _this = this;
          Vue.nextTick().then(function () {
            var $ = jQuery;
            $('.stm_lms_user_info_top__sort select').on('change', function () {
              _this.$set(_this, 'courses', []);
              _this.$set(_this, 'offset', 0);
              _this.$set(_this, 'sort', $(this).val());
              _this.getCourses();
            });
          });
        }
      }
    });
  });
})(jQuery);