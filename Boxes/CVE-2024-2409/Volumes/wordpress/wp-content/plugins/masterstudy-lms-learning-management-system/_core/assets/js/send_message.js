"use strict";

(function ($) {
  $(document).ready(function () {
    new Vue({
      el: '#stm_lms_send_fast_message',
      data: function data() {
        return {
          message: '',
          loading: false,
          status: '',
          response: '',
          open: ''
        };
      },
      methods: {
        'send_message': function send_message(user_to) {
          var vm = this;
          if (vm.message) {
            var url = stm_lms_ajaxurl + '?action=stm_lms_send_message&nonce=' + stm_lms_nonces['stm_lms_send_message'] + '&to=' + user_to + '&message=' + vm.message;
            vm.loading = true;
            vm['response'] = vm['status'] = '';
            this.$http.get(url).then(function (response) {
              vm.loading = false;
              vm['response'] = response.body['response'];
              vm['status'] = response.body['status'];
              vm['message'] = '';
              setTimeout(function () {
                vm.closeForm();
              }, 2000);
            });
          }
        },
        'openForm': function openForm() {
          this.open = 'active';
        },
        'closeForm': function closeForm() {
          this.open = '';
        }
      }
    });
  });
})(jQuery);