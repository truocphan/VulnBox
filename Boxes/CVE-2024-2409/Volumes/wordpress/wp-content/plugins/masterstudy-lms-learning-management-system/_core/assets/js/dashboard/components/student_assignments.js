"use strict";

/**
 *
 * @var stm_lms_ajaxurl
 */

stm_lms_components['student_assignments'] = {
  template: '#stm-lms-dashboard-student_assignments',
  props: ['course_id', 'student_id', 'assignment_id'],
  components: {
    back: stm_lms_components['back']
  },
  data: function data() {
    return {
      title: '',
      student_data: [],
      instructor_data: [],
      assignments: [],
      loading: true
    };
  },
  mounted: function mounted() {
    this.getAssignments();
  },
  computed: {},
  methods: {
    getAssignments: function getAssignments() {
      var _this = this;
      var url = stm_lms_ajaxurl + '?action=stm_lms_dashboard_get_student_assignments&nonce=' + stm_lms_nonces['stm_lms_dashboard_get_student_assignments'];
      _this.$http.post(url, {
        course_id: _this.course_id,
        student_id: _this.student_id,
        assignment_id: _this.assignment_id
      }).then(function (data) {
        data = data.body;
        _this.$set(_this, 'assignments', data.assignments);
        _this.$set(_this, 'student_data', data.user);
        _this.$set(_this, 'instructor_data', data.instructor);
        _this.$set(_this, 'title', data.title);
        _this.$set(_this, 'loading', false);
      });
    }
  }
};