"use strict";

/**
 *
 * @var stm_lms_ajaxurl
 */

stm_lms_components['course'] = {
  template: '#stm-lms-dashboard-course',
  props: ['id'],
  components: {
    add_user: stm_lms_components['add_user'],
    back: stm_lms_components['back']
  },
  data: function data() {
    return {
      id: 0,
      origin_title: '',
      title: '',
      loading: true,
      students: [],
      pages: 0,
      limit: 50,
      search: '',
      sort: '',
      sortDirection: 'DESC',
      page: 1
    };
  },
  mounted: function mounted() {
    var _this = this;
    _this.id = _this.$route.params.id;
    this.getStudents();
  },
  computed: {
    studentsList: function studentsList() {
      var _this = this;
      var students = _this.students.filter(function (course) {
        return course['student']['login'].toLowerCase().indexOf(_this.search.toLowerCase()) !== -1;
      });

      /*now we sort*/

      if (_this.sort !== '') {
        students = students.sort(_this.compare);
      }
      var limit = _this.limit;
      if (limit < 1) limit = 50;
      _this.pages = Math.ceil(students.length / limit);
      if (_this.page === 1) {
        return students.slice(0, limit);
      } else {
        var begin = (_this.page - 1) * limit;
        var end = (_this.page - 1) * limit + limit;
        return students.slice(begin, end);
      }
    }
  },
  methods: {
    compare: function compare(a, b) {
      var direction = this.sortDirection === 'DESC' ? -1 : 1;
      var sortA = a['student']['login'];
      var sortB = b['student']['login'];
      if (this.sort === 'progress') {
        sortA = a['progress_percent'];
        sortB = b['progress_percent'];
      }
      if (this.sort === 'time') {
        sortA = parseInt(a['start_time']);
        sortB = parseInt(b['start_time']);
      }
      if (sortA < sortB) return -direction;
      if (sortA > sortB) return direction;
      return 0;
    },
    getStudents: function getStudents() {
      var _this = this;
      _this.loading = true;
      var url = stm_lms_ajaxurl + '?action=stm_lms_dashboard_get_course_students';
      url += '&course_id=' + _this.id + '&nonce=' + stm_lms_nonces['stm_lms_dashboard_get_course_students'];
      _this.$http.get(url).then(function (data) {
        data = data.body;
        _this.loading = false;
        _this.$set(_this, 'title', data.title);
        _this.$set(_this, 'origin_title', data.origin_title);
        _this.$set(_this, 'students', data.students);
      });
    },
    toUser: function toUser(course_id, user_id) {
      this.$router.push({
        path: '/course/' + course_id + '/' + user_id
      });
    },
    deleteUserCourse: function deleteUserCourse(course_id, user, key) {
      if (!confirm('Are you sure you want to delete the student? Deleting the student will also remove their course progress and all associated data.')) return false;
      var _this = this;
      _this.$set(user, 'loading', true);
      var url = stm_lms_ajaxurl + '?action=stm_lms_dashboard_delete_user_from_course';
      url += '&course_id=' + course_id + '&user_id=' + user.user_id + '&nonce=' + stm_lms_nonces['stm_lms_dashboard_delete_user_from_course'];
      if (user.user_id === undefined) {
        url += '&user_email=' + user.student.email;
      }
      _this.$http.get(url).then(function () {
        _this.$set(user, 'loading', false);
        _this.getStudents();
      });
    },
    studentAdded: function studentAdded() {
      this.getStudents();
    },
    sortBy: function sortBy(sort) {
      var _this = this;

      /*So we need to change sort direction*/
      if (sort === _this.sort) {
        _this.sortDirection = _this.sortDirection === 'DESC' ? 'ASC' : 'DESC';
      }
      _this.sort = sort;
    }
  }
};