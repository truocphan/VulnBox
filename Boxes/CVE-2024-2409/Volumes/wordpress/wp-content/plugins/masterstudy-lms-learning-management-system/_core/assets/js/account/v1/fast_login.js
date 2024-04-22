"use strict";

(function ($) {
  /**
   * @var stm_lms_fast_login
   */
  $(document).ready(function () {
    new Vue({
      el: '#stm_lms_fast_login',
      data: function data() {
        return {
          loading: false,
          login: false,
          translations: stm_lms_fast_login['translations'],
          email: '',
          password: '',
          message: '',
          status: ''
        };
      },
      methods: {
        logIn: function logIn() {
          var vm = this;
          vm.loading = true;
          vm.$http.post(stm_lms_ajaxurl + '?action=stm_lms_fast_login&nonce=' + stm_lms_nonces['stm_lms_fast_login'], {
            user_login: vm.email,
            user_password: vm.password
          }).then(function (response) {
            vm.message = response.body['message'];
            vm.status = response.body['status'];
            vm.loading = false;
            if (vm.status !== 'error') {
              $.removeCookie('stm_lms_notauth_cart', {
                path: '/'
              });
              location.reload();
            }
          });
        },
        register: function register() {
          var vm = this;
          vm.loading = true;
          vm.$http.post(stm_lms_ajaxurl + '?action=stm_lms_fast_register&nonce=' + stm_lms_nonces['stm_lms_fast_register'], {
            email: vm.email,
            password: vm.password
          }).then(function (response) {
            vm.message = response.body['message'];
            vm.status = response.body['status'];
            vm.loading = false;
            if (vm.status !== 'error') {
              $.removeCookie('stm_lms_notauth_cart', {
                path: '/'
              });
              location.reload();
            }
          });
        }
      }
    });
  });
})(jQuery);