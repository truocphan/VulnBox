"use strict";

(function ($) {
  $(document).ready(function () {
    new Vue({
      el: '#stm_lms_google_classrooms',
      data: function data() {
        return {
          loading: false,
          courses: [],
          message: '',
          importInProgress: false,
          currentImporting: 0,
          loadingJson: false,
          credentialJson: '',
          json_message: '',
          is_error: false
        };
      },
      methods: {
        getCourses: function getCourses() {
          var vm = this;
          vm.loading = true;
          vm.status = vm.message = '';
          var url = stm_lms_ajaxurl + '?action=stm_lms_get_google_classroom_courses&nonce=' + stm_lms_nonces['stm_lms_get_google_classroom_courses'];
          vm.$http.get(url).then(function (res) {
            vm.loading = false;
            vm.message = res.body.message;
            vm.$set(vm, 'courses', res.body.courses);
            if (typeof res.body.error !== 'undefined') {
              location.reload();
            }
          });
        },
        importCourse: function importCourse(course) {
          var vm = this;
          vm.$set(course, 'loading', true);
          var url = stm_lms_ajaxurl + '?action=stm_lms_get_google_classroom_course&course_id=' + course.course_id + '&nonce=' + stm_lms_nonces['stm_lms_get_google_classroom_course'];
          vm.$http.get(url).then(function (res) {
            vm.$set(course, 'loading', false);
            vm.$set(course, 'action_links', res.body);
            if (vm.importInProgress) {
              vm.$set(vm, 'currentImporting', vm.currentImporting + 1);
              vm.importAll();
            }
          });
        },
        importAll: function importAll() {
          var vm = this;
          vm.$set(vm, 'importInProgress', true);
          if (typeof vm.courses[vm.currentImporting] !== 'undefined') {
            vm.importCourse(vm.courses[vm.currentImporting]);
          } else {
            vm.$set(vm, 'importInProgress', false);
            vm.$set(vm, 'currentImporting', 0);
          }
        },
        publishAll: function publishAll() {
          var vm = this;
          vm.$set(vm, 'importInProgress', true);
          if (typeof vm.courses[vm.currentImporting] !== 'undefined') {
            if (typeof vm.courses[vm.currentImporting]['action_links'] === 'undefined') {
              vm.$set(vm, 'currentImporting', vm.currentImporting + 1);
              vm.publishAll();
            } else {
              vm.publishCourse(vm.courses[vm.currentImporting]);
            }
          } else {
            vm.$set(vm, 'importInProgress', false);
            vm.$set(vm, 'currentImporting', 0);
          }
        },
        publishCourse: function publishCourse(course) {
          var vm = this;
          vm.$set(course, 'loading', true);
          var url = stm_lms_ajaxurl + '?action=stm_lms_get_google_classroom_publish_course' + '&course_id=' + course.course_id + '&nonce=' + stm_lms_nonces['stm_lms_get_google_classroom_publish_course'];
          vm.$http.get(url).then(function (res) {
            vm.$set(course, 'loading', false);
            vm.$set(course, 'action_links', res.body);
            if (vm.importInProgress) {
              vm.$set(vm, 'currentImporting', vm.currentImporting + 1);
              vm.publishAll();
            }
          });
        },
        uploadCredentials: function uploadCredentials() {
          var vm = this;
          var formData = new FormData();
          formData.append('file', vm.credentialJson);
          vm.loadingJson = true;
          vm.json_message = vm.is_error = '';
          vm.$http.post(stm_lms_ajaxurl + '?action=stm_lms_g_c_load_credentials', formData).then(function (res) {
            var data = res.body;
            if (typeof data.error !== 'undefined') {
              vm.json_message = data.error;
              vm.is_error = 'error';
            } else {
              vm.json_message = data.success;
              location.reload();
            }
            vm.loadingJson = false;
          });
        },
        previewFiles: function previewFiles() {
          var vm = this;
          if (typeof this.$refs.credentialsJson.files[0] !== 'undefined') {
            vm.$set(vm, 'credentialJson', this.$refs.credentialsJson.files[0]);
          }
        }
      },
      mounted: function mounted() {
        this.getCourses();
      }
    });
    new Vue({
      el: '#stm_lms_g_c_page_demo',
      data: function data() {
        return {
          loading: false,
          pageEditUrl: '',
          pageUrl: ''
        };
      },
      mounted: function mounted() {
        this.checkPage();
      },
      methods: {
        checkPage: function checkPage() {
          var vm = this;
          vm.loading = true;
          vm.status = vm.message = '';
          var url = stm_lms_ajaxurl + '?action=stm_lms_get_g_c_get_archive_page&nonce=' + stm_lms_nonces['stm_lms_get_g_c_get_archive_page'];
          vm.$http.get(url).then(function (res) {
            vm.loading = false;
            vm.pageEditUrl = res.body.edit_post_link;
            vm.pageUrl = res.body.url;
          });
        }
      }
    });
  });
})(jQuery);