"use strict";

function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
(function ($) {
  $(document).ready(function () {
    Vue.component('date-picker', DatePicker["default"]);
    new Vue({
      el: '#stm-account-statistics',
      data: {
        currency_symbol: null,
        paypal_email: null,
        paypal_email_result: null,
        paypal_email_loader: false,
        author_id: 0,
        courses: 1,
        selected_course: 0,
        loading: false,
        load_more_loading: false,
        show_load_more: false,
        limit: 10,
        height: 400,
        offset: 0,
        total: 0,
        total_price: 0,
        date_range: null,
        date_from: null,
        date_to: null,
        order_items: [],
        labels_earnings: null,
        datasets_earnings: null,
        labels_sales: [],
        datasets_sales: [{
          data: [],
          backgroundColor: []
        }]
      },
      created: function created() {
        var vm = this;
        if (typeof account_statistics_data == "undefined") return;
        if (typeof account_statistics_data.currency_symbol != "undefined") this.currency_symbol = account_statistics_data.currency_symbol;
        if (typeof account_statistics_data.author_id != "undefined") this.author_id = account_statistics_data.author_id;
        if (typeof account_statistics_data.paypal_email != "undefined") this.paypal_email = account_statistics_data.paypal_email;
        if (typeof account_statistics_data.labels_earnings != "undefined") this.labels_earnings = account_statistics_data.labels_earnings;
        if (typeof account_statistics_data.datasets_earnings != "undefined") {
          var earnings = [];
          if (_typeof(account_statistics_data.datasets_earnings) === 'object') {
            for (var stat in account_statistics_data.datasets_earnings) {
              if (account_statistics_data.datasets_earnings.hasOwnProperty(stat)) {
                earnings.push(account_statistics_data.datasets_earnings[stat]);
              }
            }
          } else {
            earnings = account_statistics_data.datasets_earnings;
          }
          this.datasets_earnings = earnings;
          earnings.forEach(function (item) {
            var result = Object.keys(item.data).map(function (key) {
              return [item.data[key]];
            });
            item.data = result;
          });
        }
        if (typeof account_statistics_data.sales_statisticas != "undefined") {
          account_statistics_data.sales_statisticas.forEach(function (item) {
            vm.labels_sales.push(item.title);
            vm.datasets_sales[0].data.push(item.order_item_count);
            vm.datasets_sales[0].backgroundColor.push(item.backgroundColor);
          });
        }
      },
      mounted: function mounted() {
        this.get_course();
        this.get_order_item();
      },
      methods: {
        save_email: function save_email() {
          var vm = this;
          if (vm.paypal_email_loader) return true;
          vm.paypal_email_loader = true;
          vm.paypal_email_result = null;
          var headers = {
            'X-WP-Nonce': stm_lms_vars.wp_rest_nonce
          };
          var formData = new FormData();
          formData.append('paypal_email', vm.paypal_email);
          this.$http.post('stm-lms-payout/paypal-email', formData, {
            headers: headers
          }).then(function (response) {
            vm.paypal_email_loader = false;
            vm.paypal_email_result = response.body;
          });
        },
        get_course: function get_course() {
          var vm = this;
          this.$http.get('stm-lms-user/course-list', {
            params: {
              author_id: vm.author_id
            }
          }).then(function (response) {
            vm.courses = [{
              id: 0,
              title: "All"
            }];
            vm.courses = vm.courses.concat(response.body);
          });
        },
        get_order_item: function get_order_item(add_items) {
          var vm = this;
          vm.loading = true;
          vm.load_more_loading = true;
          var formData = new FormData();
          var headers = {
            'X-WP-Nonce': stm_lms_vars.wp_rest_nonce
          };
          formData.append('limit', vm.limit);
          formData.append('offset', vm.offset);
          formData.append('author_id', account_statistics_data.author_id);
          formData.append('nonce', stm_lms_vars.wp_rest_nonce);
          if (vm.date_from && vm.date_to) {
            formData.append('date_from', vm.date_from);
            formData.append('date_to', vm.date_to);
          }
          if (vm.selected_course) formData.append('course_id', vm.selected_course);
          this.$http.post('stm-lms/order/items', formData, {
            headers: headers
          }).then(function (response) {
            vm.load_more_loading = false;
            if (typeof response.body.items != "undefined") {
              if (add_items) vm.order_items = vm.order_items.concat(response.body.items);else vm.order_items = response.body.items;
            }
            if (typeof response.body.total != "undefined") vm.total = response.body.total;
            if (typeof response.body.total_price != "undefined") vm.total_price = response.body.total_price;
            if (vm.total <= vm.order_items.length) vm.show_load_more = false;else vm.show_load_more = true;
          });
        },
        load_more: function load_more() {
          if (this.load_more_loading) return;
          this.load_more_loading = true;
          this.offset += this.limit;
          this.get_order_item(true);
        },
        formatPrice: function formatPrice(value) {
          var val = (value / 1).toFixed(2).replace('.', ',');
          return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        },
        dateChanged: function dateChanged(date_range) {
          this.date_from = moment(date_range[0]).format("YYYY-MM-DD");
          this.date_to = moment(date_range[1]).format("YYYY-MM-DD");
          this.offset = 0;
          this.get_order_item(false);
        }
      },
      watch: {
        date_range: function date_range(val) {
          if (!val) {
            this.date_range = null;
            this.date_from = null;
            this.offset = 0;
            this.get_order_item(false);
          }
        },
        selected_course: function selected_course(val) {
          this.offset = 0;
          this.get_order_item(false);
        }
      }
    });
  });
  Vue.component('line-chart', {
    "extends": VueChartJs.Line,
    mounted: function mounted() {
      var _masterstudy_lms_stat, _masterstudy_lms_stat2;
      var vm = this;
      this.renderChart({
        labels: vm.labels,
        datasets: vm.datasets
      }, {
        title: {
          display: true,
          text: (_masterstudy_lms_stat = (_masterstudy_lms_stat2 = masterstudy_lms_statistics_texts) === null || _masterstudy_lms_stat2 === void 0 ? void 0 : _masterstudy_lms_stat2.earnings_text) !== null && _masterstudy_lms_stat !== void 0 ? _masterstudy_lms_stat : 'Earnings',
          fontSize: 22
        },
        responsive: true,
        maintainAspectRatio: false,
        legend: {
          position: 'bottom',
          labels: {
            boxWidth: 10,
            usePointStyle: true,
            padding: 20
          }
        },
        scales: {
          yAxes: [{
            type: 'linear',
            ticks: {
              userCallback: function userCallback(tick) {
                return "$ " + tick.toString();
              }
            }
          }]
        }
      });
    },
    props: {
      labels: {
        "default": ['January', 'February', 'March', 'April', 'May', 'June']
      },
      datasets: {
        "default": []
      }
    }
  });
  Vue.component('line-pie', {
    "extends": VueChartJs.Pie,
    mounted: function mounted() {
      var _masterstudy_lms_stat3, _masterstudy_lms_stat4;
      var vm = this;
      this.renderChart({
        labels: vm.labels,
        datasets: vm.datasets
      }, {
        title: {
          display: true,
          text: (_masterstudy_lms_stat3 = (_masterstudy_lms_stat4 = masterstudy_lms_statistics_texts) === null || _masterstudy_lms_stat4 === void 0 ? void 0 : _masterstudy_lms_stat4.sales_text) !== null && _masterstudy_lms_stat3 !== void 0 ? _masterstudy_lms_stat3 : 'Sales',
          fontSize: 22
        },
        responsive: true,
        maintainAspectRatio: false,
        legend: {
          position: 'bottom',
          labels: {
            boxWidth: 10,
            usePointStyle: true,
            padding: 20
          }
        }
      });
    },
    props: {
      labels: {
        "default": ['Data One1', 'Data One2', 'Data One3', 'Data One4']
      },
      datasets: {
        "default": [{
          data: [45, 32, 4, 0],
          backgroundColor: ['rgba(248, 121, 113, 0.42)', 'rgba(44, 117, 228, 0.44)', 'rgba(47, 190, 64, 0.52)', 'rgba(47, 100, 100, 0.52)']
        }]
      }
    }
  });
})(jQuery);