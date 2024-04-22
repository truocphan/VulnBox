"use strict";

(function ($) {
  $(document).ready(function () {
    new Vue({
      el: '#my-orders',
      data: function data() {
        return {
          vue_loaded: true,
          hash: parseFloat(window.location.hash.replace(/[^0-9]/, '')),
          loading: false,
          orders: [],
          offset: 0,
          total: false
        };
      },
      mounted: function mounted() {
        this.getOrders();
      },
      methods: {
        getOrders: function getOrders() {
          var vm = this;
          var url = stm_lms_ajaxurl + '?action=stm_lms_get_user_orders&nonce=' + stm_lms_nonces['user_orders'] + '&offset=' + vm.offset;
          vm.loading = true;
          this.$http.get(url).then(function (response) {
            if (response.body['posts']) {
              response.body['posts'].forEach(function (order) {
                if (order.id === vm.hash) {
                  order['isOpened'] = true;
                  $('a[href="#my-orders"]').click();
                  Vue.nextTick().then(function () {
                    $('html, body').animate({
                      scrollTop: $('.stm-lms-user-order-' + order.id).offset().top - 200
                    }, 300);
                  });
                }
                vm.orders.push(order);
              });
            }
            vm.total = response.body['total'];
            vm.loading = false;
            vm.offset++;
          });
        },
        openTab: function openTab(key) {
          var opened = typeof this.orders[key]['isOpened'] === 'undefined' ? true : !this.orders[key]['isOpened'];
          this.$set(this.orders[key], 'isOpened', opened);
        }
      }
    });
  });
})(jQuery);