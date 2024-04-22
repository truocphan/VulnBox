"use strict";

(function ($) {
  $(document).ready(function () {
    new Vue({
      el: '#stm_lms_gradebook',
      data: function data() {
        return {
          courses: [],
          offset: 0,
          loading: true,
          course_curriculum: {},
          search: '',
          by_views: false,
          total: false
        };
      },
      mounted: function mounted() {
        this.getCourses();
      },
      computed: {
        filteredList: function filteredList() {
          var _this = this;
          return this.courses.filter(function (course) {
            return course.title.toLowerCase().includes(_this.search.toLowerCase());
          });
        }
      },
      methods: {
        compare: function compare(a, b) {
          var vm = this;
          var key = vm.by_views ? 'views' : 'time';
          if (a[key] < b[key]) return 1;
          if (a[key] > b[key]) return -1;
          return 0;
        },
        sortBy: function sortBy() {
          this.courses = this.courses.sort(this.compare);
        },
        getCourses: function getCourses() {
          var vm = this;
          var url = stm_lms_ajaxurl + '?action=stm_lms_get_instructor_courses&nonce=' + stm_lms_nonces['stm_lms_get_instructor_courses'] + '&offset=' + vm.offset + '&status=publish';
          vm.loading = true;
          this.$http.get(url).then(function (response) {
            response.body['posts'].forEach(function (course) {
              vm.courses.push(course);
            });
            vm.total = response.body['total'];
            vm.loading = false;
            vm.offset++;
          });
        },
        openCourse: function openCourse(course) {
          var vm = this;
          if (typeof course.data === 'undefined') {
            vm.$set(course, 'loading', true);
            vm.$set(course, 'opened', true);
            var url = stm_lms_ajaxurl + '?action=stm_lms_get_course_info&nonce=' + stm_lms_pro_nonces['stm_lms_get_course_info'] + '&course_id=' + course.id;
            this.$http.get(url).then(function (response) {
              vm.$set(course, 'data', response.data);
              vm.$set(course, 'loading', false);
            });
          } else {
            vm.$set(course, 'opened', !course.opened);
          }
        },
        loadStudents: function loadStudents(course) {
          var vm = this;
          vm.$set(course, 'students_loading', true);
          var url = stm_lms_ajaxurl + '?action=stm_lms_get_course_students&nonce=' + stm_lms_pro_nonces['stm_lms_get_course_students'] + '&course_id=' + course.id;
          this.$http.get(url).then(function (response) {
            vm.$set(course, 'students', response.body.course_students);
            vm.$set(course, 'students_loading', false);
            vm.$set(vm, 'course_curriculum', response.body.course_curriculum);
          });
        },
        loadMore: function loadMore() {
          var vm = this;
          vm.getCourses();
        }
      }
    });
  });
})(jQuery);