"use strict";

(function ($) {
  $(document).ready(function () {
    new Vue({
      el: '#stm_lms_points_history',
      data: function data() {
        return {
          points: stm_lms_points_history['result']['points'],
          pages: stm_lms_points_history['result']['pages'],
          page: 1,
          loading: false,
          sum: stm_lms_points_history['result']['sum']
        };
      },
      methods: {
        getPoints: function getPoints() {
          var vm = this;
          vm.loading = true;
          var url = stm_lms_ajaxurl + '?action=stm_lms_get_user_points_history&nonce=' + stm_lms_nonces['stm_lms_get_user_points_history'] + '&page=' + vm.page;
          vm.$http.get(url).then(function (r) {
            var res = r.body;
            vm.loading = false;
            vm.$set(vm, 'points', res.points);
          });
        },
        computePage: function computePage(page) {
          /*Always show first and last page*/
          if (page === 1 || page === this.pages) return 'first';

          /*Hide not 2 closest pages to first and last*/
          if (page + 2 < this.page) return 'other';
          if (page - 2 > this.page) return 'other';
          return 'first';
        }
      }
    });
  });
})(jQuery);