"use strict";

/**
 * @var google_classroom_data
 */

(function ($) {
  $(document).ready(function () {
    if (!$('#stm_lms_google_classroom_grid').length) return false;
    new Vue({
      el: '#stm_lms_google_classroom_grid',
      data: function data() {
        return {
          loading: false,
          courses: [],
          more: false,
          auditory: google_classroom_data['chosen_auditory'],
          auditories: google_classroom_data['auditory'],
          pages: 1,
          page: 1,
          colors: google_classroom_data['colors'],
          per_page: google_classroom_data['per_page']
        };
      },
      methods: {
        getCourses: function getCourses() {
          var vm = this;
          vm.loading = true;
          var url = stm_lms_ajaxurl + '?action=stm_lms_get_google_classroom_courses_module&page=' + vm.page + '&auditory=' + vm.auditory + '&per_page=' + vm.per_page;
          vm.$http.get(url).then(function (res) {
            vm.loading = false;
            vm.message = res.body.message;
            vm.$set(vm, 'courses', res.body.courses);
            vm.$set(vm, 'pages', res.body.pages);
          });
        },
        copyCode: function copyCode(course) {
          var vm = this;
          Vue.nextTick(function () {
            var testingCodeToCopy = document.querySelector('#code_' + course.meta['code']);
            testingCodeToCopy.setAttribute('type', 'text');
            testingCodeToCopy.select();
            vm.$set(course, 'copied', true);
            try {
              document.execCommand('copy');
              setTimeout(function () {
                vm.$set(course, 'copied', false);
              }, 1000);
            } catch (err) {}

            /* unselect the range */
            testingCodeToCopy.setAttribute('type', 'hidden');
            window.getSelection().removeAllRanges();
          });
        }
      },
      mounted: function mounted() {
        var vm = this;
        vm.getCourses();
        Vue.nextTick(function () {
          jQuery.cookie('google_classroom_popup', 'viewed', {
            path: '/'
          });
          if (vm.auditory) {
            $([document.documentElement, document.body]).animate({
              scrollTop: $("#stm_lms_google_classroom_grid").offset().top
            }, 1200);
          }
        });
      }
    });
  });
})(jQuery);