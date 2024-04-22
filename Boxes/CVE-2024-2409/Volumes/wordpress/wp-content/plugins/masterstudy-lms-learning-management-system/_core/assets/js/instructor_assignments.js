"use strict";

(function ($) {
  var myVar;
  $(document).ready(function () {
    $('body').addClass('instructor-assignments');
    new Vue({
      el: '#stm_lms_instructor_assignments',
      data: function data() {
        return {
          loading: false,
          assignments: window['stm_lms_assignments']['tasks'].posts,
          total: window['stm_lms_assignments']['tasks'].total,
          pages: window['stm_lms_assignments']['tasks'].pages,
          page: 1,
          s: '',
          active_sort: false,
          courses: window['stm_lms_assignments']['courses'].posts,
          active_course: false,
          course_search: '',
          course_loading: false
        };
      },
      mounted: function mounted() {
        this.getAssignmentsData();
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
        getAssignmentsData: function getAssignmentsData() {
          var vm = this;
          vm.assignments.forEach(function (assignment) {
            vm.getAssignmentData(assignment);
          });
        },
        getAssignmentData: function getAssignmentData(assignment) {
          var vm = this;
          var url = stm_lms_ajaxurl + '?action=stm_lms_get_assignment_data&id=' + assignment['id'] + '&nonce=' + stm_lms_nonces['stm_lms_get_assignment_data'];
          vm.$set(assignment, 'loading', true);
          this.$http.get(url).then(function (response) {
            response = response.body;
            vm.$set(assignment, 'loading', false);
            vm.$set(assignment, 'data', response);
            if (response.pending > response.pending_watched) {
              vm.$set(assignment, 'viewed', false);
            }
          });
        },
        getAssignments: function getAssignments() {
          var vm = this;
          if (vm.loading) return false;
          var url = stm_lms_ajaxurl + '?action=stm_lms_get_instructor_assingments&nonce=' + stm_lms_nonces['stm_lms_get_instructor_assingments'] + '&page=' + vm.page + '&course_id=' + vm.active_course.id + '&s=' + vm.s;
          vm.$set(vm, 'loading', true);
          this.$http.get(url).then(function (response) {
            response = response.body;
            vm.$set(vm, 'assignments', response.posts);
            vm.$set(vm, 'total', response.total);
            vm.$set(vm, 'pages', response.pages);
            vm.$set(vm, 'loading', false);
            vm.getAssignmentsData();
          });
        },
        getCourses: function getCourses() {
          var vm = this;
          if (vm.loading) return false;
          var url = stm_lms_ajaxurl + '?action=stm_lms_get_instructor_courses&nonce=' + stm_lms_nonces['stm_lms_get_instructor_courses'] + '&pp=5' + '&s=' + vm.course_search;
          vm.$set(vm, 'course_loading', true);
          this.$http.get(url).then(function (response) {
            response = response.body;
            vm.$set(vm, 'course_loading', false);
            vm.$set(vm, 'courses', response.posts);
          });
        },
        initCoursesSearch: function initCoursesSearch() {
          var vm = this;
          clearTimeout(myVar);
          myVar = setTimeout(function () {
            vm.getCourses();
          }, 1000);
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