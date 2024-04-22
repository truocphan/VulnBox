"use strict";

(function ($) {
  $(document).ready(function () {
    new Vue({
      el: '#stm_lms_admin_point_stats',
      data: function data() {
        return {
          loading: false,
          users: stm_lms_point_stats['data']['users'],
          total_users: stm_lms_point_stats['data']['total'],
          pages: stm_lms_point_stats['data']['pages'],
          translations: stm_lms_point_stats['translation'],
          page: 1,
          search: ''
        };
      },
      mounted: function mounted() {},
      methods: {
        getUsers: function getUsers() {
          var vm = this;
          vm.loading = true;
          var url = stm_lms_ajaxurl + '?action=stm_lms_get_point_users&nonce=' + stm_lms_nonces['stm_lms_get_point_users'] + '&page=' + vm.page + '&s=' + vm.search;
          vm.$http.get(url).then(function (r) {
            var res = r.body;
            vm.loading = false;
            vm.$set(vm, 'users', res.users);
            vm.$set(vm, 'pages', res.pages);
          });
        },
        loadHistory: function loadHistory(user, page) {
          var vm = this;
          vm.$set(user, 'loading', true);
          vm.$set(user, 'current_page', page);
          var url = stm_lms_ajaxurl + '?action=stm_lms_get_user_points_history_admin&nonce=' + stm_lms_nonces['stm_lms_get_user_points_history_admin'] + '&page=' + page + '&user_id=' + user['ID'];
          vm.$http.get(url).then(function (r) {
            var res = r.body;
            vm.$set(user, 'loading', false);
            vm.$set(user, 'history', {});
            vm.$set(user.history, 'pages', res.pages);
            vm.$set(user.history, 'points', res.points);
            vm.$set(user.history, 'sum', res.sum);
          });
        },
        changePoints: function changePoints(point, user) {
          var vm = this;
          if (typeof point.edit === 'string') {
            vm.$set(point, 'loading', true);
            var current_point = parseFloat(point.edit.match(/(\d+)/)[0]);
            var point_score = parseFloat(point.score.match(/(\d+)/)[0]);
            var url = stm_lms_ajaxurl + '?action=stm_lms_change_points&nonce=' + stm_lms_nonces['stm_lms_change_points'] + '&point=' + current_point + '&user_points_id=' + point['user_points_id'] + '&user_id=' + point['user_id'] + '&prev_point=' + point['score'];
            vm.$http.get(url).then(function (r) {
              var res = r.body;
              vm.$set(point, 'score', res.point);
              vm.$set(point, 'editing', false);
              vm.$set(point, 'loading', false);
              vm.$set(user.history, 'sum', res.total);
              vm.$set(user.data['lms_data'], 'points', res.total);
            });
          }
        },
        deletePoint: function deletePoint(point, pointIndex, user) {
          if (!confirm(this.translations['delete_action'])) return false;
          var vm = this;
          vm.$set(point, 'loading', true);
          var url = stm_lms_ajaxurl + '?action=stm_lms_delete_points&nonce=' + stm_lms_nonces['stm_lms_delete_points'] + '&user_points_id=' + point['user_points_id'] + '&user_id=' + point['user_id'];
          vm.$http.get(url).then(function (r) {
            var res = r.body;
            vm.$set(point, 'loading', false);
            vm.$set(user.history, 'sum', res.total);
            user.history.points.splice(pointIndex, 1);
          });
        }
      }
    });
  });
})(jQuery);