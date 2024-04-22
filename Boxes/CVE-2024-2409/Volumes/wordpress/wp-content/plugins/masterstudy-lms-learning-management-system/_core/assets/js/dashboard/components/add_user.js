"use strict";

stm_lms_components['add_user'] = {
  template: '#stm-lms-dashboard-add_user',
  props: ['course_id', 'title'],
  data: function data() {
    return {
      loading: false,
      active: false,
      email: '',
      status: '',
      message: ''
    };
  },
  mounted: function mounted() {},
  methods: {
    addStudent: function addStudent() {
      var _this = this;
      _this.status = _this.message = '';
      if (_this.email === '') {
        alert('Enter email');
        return false;
      }
      if (!_this.validateEmail(_this.email)) {
        alert('Enter valid email');
        return false;
      }
      _this.loading = true;
      var url = stm_lms_ajaxurl + '?action=stm_lms_dashboard_add_user_to_course' + '&nonce=' + stm_lms_nonces['stm_lms_dashboard_add_user_to_course'];
      _this.$http.post(url, {
        email: _this.email,
        course_id: _this.course_id
      }).then(function (data) {
        data = data.body;
        _this.status = data.status;
        _this.message = data.message;
        _this.loading = false;
        _this.email = '';
        _this.$emit('studentAdded');
      });
    },
    validateEmail: function validateEmail(email) {
      var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      return re.test(String(email).toLowerCase());
    }
  }
};