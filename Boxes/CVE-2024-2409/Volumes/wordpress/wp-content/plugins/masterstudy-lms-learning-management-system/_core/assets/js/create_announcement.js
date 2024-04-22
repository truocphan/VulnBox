"use strict";

(function ($) {
  $(document).ready(function () {
    new Vue({
      el: '#stm_lms_create_announcement',
      data: function data() {
        return {
          posts: {},
          post_id: '',
          mail: '',
          loading: false,
          message: '',
          status: 'error'
        };
      },
      mounted: function mounted() {
        this.getInstructorCourses();
      },
      methods: {
        getInstructorCourses: function getInstructorCourses() {
          var vm = this;
          var url = stm_lms_ajaxurl + '?action=stm_lms_get_instructor_courses&nonce=' + stm_lms_nonces['stm_lms_get_instructor_courses'] + '&offset=0&pp=-1&ids_only=1';
          vm.loading = true;
          vm.message = 'Loading Your Courses';
          vm.status = 'success';
          this.$http.get(url).then(function (response) {
            vm.loading = false;
            vm.message = vm.status = '';
            if (response['body']['posts']) {
              var p = response['body']['posts'];
              for (var key in p) {
                if (p.hasOwnProperty(key)) {
                  Vue.set(vm.posts, key, p[key]);
                }
              }
            }
          });
        },
        createAnnouncement: function createAnnouncement() {
          var vm = this;
          var url = stm_lms_ajaxurl + '?action=stm_lms_create_announcement&nonce=' + stm_lms_pro_nonces['stm_lms_create_announcement'] + '&post_id=' + vm.post_id + '&mail=' + vm.mail;
          vm.loading = true;
          vm.message = vm.status = '';
          this.$http.get(url).then(function (response) {
            vm.loading = false;
            vm.message = response.body['message'];
            vm.status = response.body['status'];
          });
        }
      }
    });
  });
})(jQuery);