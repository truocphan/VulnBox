"use strict";

Vue.component('demo_import', {
  data: function data() {
    return {
      isLoading: false,
      currentStep: 0,
      steps: ['questions', 'quizzes', 'lessons', 'courses'],
      isDone: false,
      importStarted: false,
      doneSteps: 'questions'
    };
  },
  mounted: function mounted() {},
  methods: {
    importData: function importData() {
      var _this = this;
      _this.$set(_this, 'importStarted', true);
      if (typeof _this.steps[_this.currentStep] !== 'undefined' && !_this.isLoading) {
        _this.$set(_this, 'isLoading', true);
        _this.$http.get(stm_lms_ajaxurl + '?action=stm_lms_import_sample_data&stm_lms_step=' + _this.steps[_this.currentStep]).then(function (r) {
          r = r.body;
          _this.$set(_this, 'currentStep', _this.currentStep + 1);
          _this.$set(_this, 'doneSteps', _this.doneSteps + ' ' + _this.steps[_this.currentStep]);
          _this.$set(_this, 'isLoading', false);
          _this.importData();
        });
      } else {
        _this.$set(_this, 'isDone', true);
        _this.$set(_this, 'doneSteps', _this.doneSteps + ' complete');
      }
    }
  }
});