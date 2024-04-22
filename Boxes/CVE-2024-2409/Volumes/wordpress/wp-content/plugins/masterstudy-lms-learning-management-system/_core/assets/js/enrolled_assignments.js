"use strict";

(function ($) {
  var myVar;
  $(document).ready(function () {
    (function ($) {
      var myVar;
      $(document).ready(function () {
        $('body').addClass('enrolled-assignments');
        new Vue({
          el: '#stm_lms_enrolled_assignments',
          data: function data() {
            return {
              loading: false,
              pages: window['stm_lms_enrolled_assignments']['assignments'].pages,
              assignments: window['stm_lms_enrolled_assignments']['assignments'],
              s: '',
              active_sort: false,
              statuses: window['stm_lms_enrolled_assignments']['statuses'],
              active_status: false,
              page: 1
            };
          },
          mounted: function mounted() {
            this.getAssignments();
          },
          methods: {
            computePage: function computePage(page) {
              /*Always show first and last page*/
              if (page === 1 || page === this.pages) return 'first';

              /*Hide not 2 closest pages to first and last*/
              if (page + 2 < this.page) return 'other';
              if (page - 2 > this.page) return 'other';
              return 'first';
            },
            getAssignments: function getAssignments() {
              var vm = this;
              if (vm.loading) return false;
              var url = stm_lms_ajaxurl + '?action=stm_lms_get_enrolled_assignments&nonce=' + stm_lms_nonces['stm_lms_get_enrolled_assingments'] + '&page=' + vm.page + '&status=' + vm.active_status.id + '&s=' + vm.s;
              vm.$set(vm, 'loading', true);
              this.$http.get(url).then(function (response) {
                response = response.body;
                vm.$set(vm, 'loading', false);
                if (response.length > 0) {
                  vm.$set(vm, 'assignments', response);
                  vm.$set(vm, 'pages', response[0].pages);
                  this.pages = response[0].pages;
                }
              });
            },
            initSearch: function initSearch() {
              var vm = this;
              clearTimeout(myVar);
              myVar = setTimeout(function () {
                vm.$set(vm, 'page', 1);
                vm.getAssignments();
              }, 1000);
            }
          }
        });
      });
    })(jQuery);
  });
})(jQuery);