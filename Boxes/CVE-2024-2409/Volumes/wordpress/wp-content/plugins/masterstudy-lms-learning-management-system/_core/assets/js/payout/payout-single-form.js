"use strict";

new Vue({
  el: "#stm-payout-form",
  data: {
    mesage: null,
    loader: false
  },
  methods: {
    pay_now: function pay_now(id) {
      var vm = this;
      vm.loader = true;
      vm.mesage = null;
      this.$http.post(stm_payout_form_url_data['url'] + '/stm-lms-payout/pay-now/' + id, {}, {
        headers: {
          'X-WP-Nonce': ms_lms_nonce
        }
      }).then(function (response) {
        vm.loader = false;
        if (response.body.success) {
          location.reload();
          vm.mesage = response.body.message;
        } else {
          vm.mesage = response.body.message;
        }
      });
    },
    payed: function payed(id) {
      var vm = this;
      vm.loader = true;
      vm.mesage = null;
      this.$http.post(stm_payout_form_url_data['url'] + '/stm-lms-payout/payed/' + id, {}, {
        headers: {
          'X-WP-Nonce': ms_lms_nonce
        }
      }).then(function (response) {
        vm.loader = false;
        if (response.body.success) {
          location.reload();
          vm.mesage = response.body.message;
        } else {
          vm.mesage = response.body.message;
        }
      });
    }
  }
});