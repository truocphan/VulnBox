"use strict";

/**
 *
 * @var stm_lms_ajaxurl
 */

stm_lms_components['student_quiz'] = {
  template: '#stm-lms-dashboard-student_quiz',
  props: ['course_id', 'student_id', 'quiz_id'],
  components: {
    back: stm_lms_components['back'],
    single_quiz: stm_lms_components['single_quiz']
  },
  data: function data() {
    return {
      quizzes: [],
      loading: true
    };
  },
  mounted: function mounted() {
    this.getQuizzes();
  },
  computed: {},
  methods: {
    getQuizzes: function getQuizzes() {
      var _this = this;
      var url = stm_lms_ajaxurl + '?action=stm_lms_dashboard_get_student_quizzes&nonce=' + stm_lms_nonces['stm_lms_dashboard_get_student_quizzes'];
      _this.$http.post(url, {
        course_id: _this.course_id,
        student_id: _this.student_id,
        quiz_id: _this.quiz_id
      }).then(function (data) {
        data = data.body;
        _this.$set(_this, 'quizzes', data);
        _this.$set(_this, 'loading', false);
      });
    },
    openQuiz: function openQuiz(quiz, attempt) {
      var _this = this;
      var opened = typeof quiz.opened === 'undefined' ? false : quiz.opened;
      _this.$set(quiz, 'opened', !opened);
      _this.getQuizData(quiz, attempt);
    },
    getQuizData: function getQuizData(quiz, attempt) {
      var _this = this;
      _this.$set(quiz, 'loading', true);
      var url = stm_lms_ajaxurl + '?action=stm_lms_dashboard_get_student_quiz&nonce=' + stm_lms_nonces['stm_lms_dashboard_get_student_quiz'];
      _this.$http.post(url, {
        course_id: _this.course_id,
        student_id: _this.student_id,
        quiz_id: _this.quiz_id,
        user_quiz_id: quiz.user_quiz_id,
        attempt: attempt
      }).then(function (data) {
        data = data.body;
        _this.$set(quiz, 'data_html', data);
        _this.$set(quiz, 'loading', false);
      });
    }
  }
};