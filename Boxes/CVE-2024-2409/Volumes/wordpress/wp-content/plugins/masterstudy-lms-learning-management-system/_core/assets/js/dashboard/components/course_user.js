"use strict";

/**
 *
 * @var stm_lms_ajaxurl
 */

stm_lms_components['course_user'] = {
  template: '#stm-lms-dashboard-course_user',
  props: ['id', 'user_id'],
  components: {
    back: stm_lms_components['back'],
    student_assignments: stm_lms_components['student_assignments'],
    student_quiz: stm_lms_components['student_quiz'],
    single_quiz: stm_lms_components['single_quiz']
  },
  data: function data() {
    return {
      course_title: '',
      id: 0,
      user_id: 0,
      data: [],
      loading: true
    };
  },
  mounted: function mounted() {
    var _this = this;
    _this.id = _this.$route.params.id;
    _this.user_id = _this.$route.params.user_id;
    _this.getStudentProgress();
  },
  computed: {},
  methods: {
    openAssignments: function openAssignments(item) {
      var _this = this;
      if (typeof item.opened === 'undefined') {
        _this.$set(item, 'opened', true);
        item.opened = true;
      } else {
        _this.$set(item, 'opened', !item.opened);
      }
    },
    getStudentProgress: function getStudentProgress() {
      var _this = this;
      var url = stm_lms_ajaxurl + '?action=stm_lms_dashboard_get_student_progress&nonce=' + stm_lms_nonces['stm_lms_dashboard_get_student_progress'];
      _this.$http.post(url, {
        course_id: _this.id,
        user_id: _this.user_id
      }).then(function (data) {
        data = data.body;
        _this.loading = false;
        _this.$set(_this, 'data', data);
      });
    },
    completeItem: function completeItem(item) {
      var _this = this;
      _this.$set(item, 'loading', true);
      var url = stm_lms_ajaxurl + '?action=stm_lms_dashboard_set_student_item_progress&nonce=' + stm_lms_nonces['stm_lms_dashboard_set_student_item_progress'];
      _this.$http.post(url, {
        course_id: _this.id,
        user_id: _this.user_id,
        item_id: item.post_id,
        completed: item.completed
      }).then(function (data) {
        data = data.body;
        _this.$set(item, 'loading', false);
        _this.$set(_this, 'data', data);
      });
    },
    resetAllProgress: function resetAllProgress(message) {
      if (!confirm(message)) return false;
      var _this = this;
      _this.$set(_this, 'loading', true);
      var url = stm_lms_ajaxurl + '?action=stm_lms_dashboard_reset_student_progress&nonce=' + stm_lms_nonces['stm_lms_dashboard_reset_student_progress'];
      _this.$http.post(url, {
        course_id: _this.id,
        user_id: _this.user_id
      }).then(function (data) {
        data = data.body;
        _this.$set(_this, 'loading', false);
        _this.$set(_this, 'data', data);
      });
    }
  }
};