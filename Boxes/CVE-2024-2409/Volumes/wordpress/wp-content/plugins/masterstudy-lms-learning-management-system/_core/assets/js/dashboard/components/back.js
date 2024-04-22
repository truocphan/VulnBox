"use strict";

stm_lms_components['back'] = {
  template: '#stm-lms-dashboard-back',
  methods: {
    goBack: function goBack() {
      this.$router.go(-1);
    }
  }
};