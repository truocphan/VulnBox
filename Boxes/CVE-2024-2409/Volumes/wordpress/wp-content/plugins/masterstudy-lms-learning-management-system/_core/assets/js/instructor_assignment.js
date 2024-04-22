"use strict";

(function ($) {
  $(document).ready(function () {
    $('body').addClass('instructor-assignment');
    new Vue({
      el: '#stm_lms_instructor_assignment',
      data: function data() {
        return {
          loading: false,
          assignments: window['stm_lms_assignment']['response']['assignments'],
          sortStatuses: {},
          sort: 'pending',
          activeSort: {},
          openSort: false,
          pages: window['stm_lms_assignment']['response']['pages'],
          page: 1
        };
      },
      created: function created() {
        this.$set(this, 'sortStatuses', window['stm_lms_assignment']['sort']);
        this.$set(this, 'activeSort', this.sortStatuses[this.sort]);
        this.$set(this, 'assignments', window['stm_lms_assignment']['response']['assignments']);
      },
      methods: {
        getAssignments: function getAssignments() {
          var vm = this;
          if (vm.loading) return false;
          var url = stm_lms_ajaxurl + '?action=stm_lms_get_user_assingments&nonce=' + stm_lms_nonces['stm_lms_get_user_assingments'] + '&id=' + window['stm_lms_assignment']['assignment_id'] + '&page=' + vm.page + '&status=' + vm.sort;
          vm.$set(vm, 'loading', true);
          this.$http.get(url).then(function (response) {
            response = response.body;
            vm.$set(vm, 'assignments', response.assignments);
            vm.$set(vm, 'pages', response.pages);
            vm.$set(vm, 'loading', false);
          });
        }
      },
      watch: {
        sort: function sort() {
          this.getAssignments();
        }
      }
    });
  });
})(jQuery);