"use strict";

Vue.component('stm-payout', {
  data: function data() {
    return {
      payout_methods: []
    };
  },
  created: function created() {
    if (typeof stm_payout_data == "undefined") return true;
    if (typeof stm_payout_data.payout_methods != "undefined") this.payout_methods = stm_payout_data.payout_methods;
  },
  methods: {
    set_default: function set_default(payout_method) {
      var vm = this;
      payout_method.loader = true;
      var formData = new FormData();
      formData.append('payment_method', payout_method.payment_method);
      this.$http.post(stm_payout_url_data['url'] + '/stm-lms-payout/payment/set_default', formData, {
        headers: {
          'X-WP-Nonce': ms_lms_nonce
        }
      }).then(function (response) {
        payout_method.loader = false;
        if (response.body.success) {
          vm.payout_methods.forEach(function (item) {
            item.is_default = false;
          });
          payout_method.is_default = true;
        }
      });
    },
    install: function install(payout_method) {
      var formData = new FormData();
      formData.append('StmLmsPaymentMethod[payment_method]', payout_method.payment_method);
      formData.append('StmLmsPaymentMethod[type]', 'install');
      payout_method.loader = true;
      this.$http.post(stm_payout_url_data['url'] + '/stm-lms-payout/settings', formData, {
        headers: {
          'X-WP-Nonce': ms_lms_nonce
        }
      }).then(function (response) {
        if (response.body.status == 'success') {
          //alert(response.body.message)
          payout_method.is_active = true;
          //	location.reload();
        }

        payout_method.loader = false;
      });
    },
    uninstall: function uninstall(payout_method) {
      var formData = new FormData();
      formData.append('StmLmsPaymentMethod[payment_method]', payout_method.payment_method);
      formData.append('StmLmsPaymentMethod[type]', 'uninstall');
      payout_method.loader = true;
      this.$http.post(stm_payout_url_data['url'] + '/stm-lms-payout/settings', formData, {
        headers: {
          'X-WP-Nonce': ms_lms_nonce
        }
      }).then(function (response) {
        if (response.body.status == 'success') {
          //alert(response.body.message)
          payout_method.is_active = false;
          //location.reload();
        }

        payout_method.loader = false;
      });
    }
  }
});