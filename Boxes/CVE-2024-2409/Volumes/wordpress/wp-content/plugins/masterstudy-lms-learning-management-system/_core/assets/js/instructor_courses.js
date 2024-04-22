"use strict";

(function ($) {
  $(document).ready(function () {
    new Vue({
      el: '#stm-lms-courses-grid',
      data: {
        vue_loaded: true,
        courses: [],
        allCourses: [],
        loading: true,
        offset: 0,
        loggedUserId: 0,
        total: false,
        quota: {},
        statuses: [],
        selectedStatus: 'all'
      },
      created: function created() {
        this.offset = parseInt(masterstudy_lms_settings_coming_soon.per_page);
        this.loggedUserId = parseInt(masterstudy_lms_settings_coming_soon.logged_user);
        this.statuses = masterstudy_lms_settings_coming_soon.course_statuses;
        this.getCourses();
      },
      methods: {
        getCourses: function getCourses() {
          var _this = this;
          var postMeta = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';
          var vm = this;
          vm.loading = true;
          vm.allCourses = [];
          var apiUrl = "".concat(ms_lms_resturl, "/courses?per_page=").concat(this.offset, "&user=").concat(this.loggedUserId);
          if (postMeta) {
            apiUrl += "&status=".concat(postMeta);
          }
          fetch(apiUrl, {
            method: 'GET',
            headers: {
              'X-WP-Nonce': masterstudy_lms_settings_coming_soon.nonce,
              'Content-Type': 'application/json'
            }
          }).then(function (response) {
            if (!response.ok) {
              throw new Error('There was a problem with the fetch operation');
            }
            return response.json();
          }).then(function (data) {
            data = JSON.parse(data);
            data.posts.forEach(function (course) {
              vm.allCourses.push(course);
            });
            if (data.found <= data.per_page) {
              vm.total = true;
            }
            vm.statuses = _this.statuses;
            vm.loading = false;
          })["catch"](function (error) {
            console.error('There was a problem with the fetch operation:', error);
          });
          vm.courses = vm.allCourses;
        },
        loadCourses: function loadCourses() {
          this.offset = this.offset + this.offset;
          this.getCourses();
        },
        changeFeatured: function changeFeatured(course) {
          var vm = this;
          var url = stm_lms_ajaxurl + '?action=stm_lms_change_featured&nonce=' + stm_lms_nonces['stm_lms_change_featured'] + '&post_id=' + course.id;
          this.$set(course, 'changingFeatured', true);
          this.$http.get(url).then(function (response) {
            vm.$set(vm, 'quota', response.body);
            vm.$set(course, 'changingFeatured', false);
            vm.$set(course, 'is_featured', response.body['featured']);
          });
        },
        changeStatus: function changeStatus(course, status) {
          var vm = this;
          var url = "".concat(stm_lms_ajaxurl, "?action=stm_lms_change_course_status&post_id=").concat(course.id, "&status=").concat(status) + '&nonce=' + stm_lms_nonces['stm_lms_change_course_status'];
          vm.$set(course, 'changingStatus', true);
          vm.$http.get(url).then(function (response) {
            vm.$set(course, 'changingStatus', false);
            vm.$set(course, 'status', response.body);
          });
        },
        filterCoursesByStatus: function filterCoursesByStatus(status) {
          this.getCourses(status);
          $(this).toggleClass("clicked");
          this.selectedStatus = status;
        }
      }
    });
  });
})(jQuery);
function stmLmsGoToHash() {
  var $ = jQuery;
  var hash = window.location.hash;
  if (hash) {
    var $selector = $('.nav-tabs a[href="' + hash + '"]');
    if (!$selector.length) return false;
    $selector.click();
    $([document.documentElement, document.body]).animate({
      scrollTop: $selector.offset().top
    }, 500);
  }
}