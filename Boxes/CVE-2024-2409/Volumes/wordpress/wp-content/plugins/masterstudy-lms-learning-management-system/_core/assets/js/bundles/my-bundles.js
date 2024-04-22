"use strict";

function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
(function ($) {
  $(document).ready(function () {
    new Vue({
      el: '#stm_lms_my_course_bundles',
      data: function data() {
        return {
          bundles: stm_lms_my_bundles['list']['posts'],
          courses: stm_lms_my_bundles['list']['courses'],
          pages: stm_lms_my_bundles['list']['pages'],
          page: 1,
          loading: false
        };
      },
      methods: {
        getBundles: function getBundles() {
          var vm = this;
          vm.loading = true;
          var url = stm_lms_ajaxurl + '?action=stm_lms_get_user_bundles&nonce=' + stm_lms_nonces['stm_lms_get_user_bundles'] + '&page=' + vm.page;
          vm.$http.get(url).then(function (r) {
            var res = r.body;
            vm.loading = false;
            vm.$set(vm, 'bundles', res.posts);
            vm.$set(vm, 'courses', res.courses);
            vm.$set(vm, 'pages', res.pages);
          });
        },
        bundleRating: function bundleRating(bundle) {
          var vm = this;
          var total = {
            count: 0,
            average: 0,
            percent: 0
          };
          if (_typeof(bundle.courses) === 'object') {
            for (var course_key in bundle.courses) {
              var course = bundle.courses[course_key];
              if (_typeof(vm.courses[course].average) && vm.courses[course].percent) {
                total.count++;
                total.average += vm.courses[course].average;
                total.percent += vm.courses[course].percent;
              }
            }
          } else {
            bundle.courses.forEach(function (course) {
              if (_typeof(vm.courses[course].average) && vm.courses[course].percent) {
                total.count++;
                total.average += vm.courses[course].average;
                total.percent += vm.courses[course].percent;
              }
            });
          }
          if (total.count) {
            total.average = (total.average / total.count).toFixed(1);
            total.percent = (total.percent / total.count).toFixed(1);
          }
          return total;
        },
        bundlePrice: function bundlePrice(bundle) {
          var vm = this;
          var total = 0;
          if (_typeof(bundle.courses) === 'object') {
            for (var course_key in bundle.courses) {
              var course = bundle.courses[course_key];
              if (vm.courses[course]['simple_price']) total += parseFloat(vm.courses[course]['simple_price']);
            }
          } else {
            bundle.courses.forEach(function (course) {
              if (vm.courses[course]['simple_price']) total += parseFloat(vm.courses[course]['simple_price']);
            });
          }
          return stm_lms_price_format(total);
        },
        deleteBundle: function deleteBundle(bundle) {
          if (!confirm('Do you really want to delete this bundle?')) return false;
          var vm = this;
          vm.loading = true;
          var url = stm_lms_ajaxurl + '?action=stm_lms_delete_bundle&nonce=' + stm_lms_nonces['stm_lms_delete_bundle'] + '&bundle_id=' + bundle.id;
          vm.$http.get(url).then(function (r) {
            var res = r.body;
            vm.getBundles();
          });
        },
        changeStatusBundle: function changeStatusBundle(bundle) {
          var vm = this;
          vm.loading = true;
          var url = stm_lms_ajaxurl + '?action=stm_lms_change_bundle_status&nonce=' + stm_lms_nonces['stm_lms_change_bundle_status'] + '&bundle_id=' + bundle.id;
          vm.$http.get(url).then(function (r) {
            var res = r.body;
            if (res !== 'OK') {
              alert(res);
            }
            vm.getBundles();
          });
        }
      }
    });
  });
})(jQuery);