"use strict";

(function ($) {
  $(document).ready(function () {
    new Vue({
      el: '#stm_lms_enterprise_group',
      data: function data() {
        return {
          loading: false,
          group_id: window['stm_lms_group']['id'],
          translations: window['stm_lms_group']['translate'],
          group: []
        };
      },
      mounted: function mounted() {
        this.fetchGroup();
      },
      computed: {},
      methods: {
        fetchGroup: function fetchGroup() {
          var vm = this;
          var url = stm_lms_ajaxurl + '?action=stm_lms_get_enterprise_group&group_id=' + vm.group_id + '&nonce=' + stm_lms_nonces['stm_lms_get_enterprise_group'];
          vm.loading = true;
          this.$http.get(url).then(function (response) {
            vm.$set(vm, 'group', response.body);
            vm.loading = false;
          });
        },
        openUser: function openUser(user) {
          var vm = this;
          if (typeof user.courses === 'undefined') {
            vm.getUserCourses(user);
          } else {
            vm.$set(user, 'active', !user.active);
          }
        },
        getUserCourses: function getUserCourses(user) {
          var vm = this;
          var url = stm_lms_ajaxurl + '?action=stm_lms_get_user_ent_courses&user_id=' + user.id + '&group_id=' + vm.group_id + '&nonce=' + stm_lms_nonces['stm_lms_get_user_ent_courses'];
          vm.$set(user, 'loading', true);
          vm.$set(user, 'active', true);
          this.$http.get(url).then(function (response) {
            vm.$set(user, 'courses', response.body);
            vm.$set(user, 'loading', false);
          });
        },
        deleteUserCourse: function deleteUserCourse(user, course) {
          var vm = this;
          var url = stm_lms_ajaxurl + '?action=stm_lms_delete_user_ent_courses&user_id=' + user.id + '&group_id=' + course.group_id + '&course_id=' + course.course_id + '&nonce=' + stm_lms_nonces['stm_lms_delete_user_ent_courses'];
          vm.$set(course, 'loading', true);
          this.$http.get(url).then(function (response) {
            vm.$set(course, 'added', false);
            vm.$set(course, 'loading', false);
          });
        },
        addUserCourse: function addUserCourse(user, course) {
          var vm = this;
          var url = stm_lms_ajaxurl + '?action=stm_lms_add_user_ent_courses&user_id=' + user.id + '&group_id=' + course.group_id + '&course_id=' + course.course_id + '&nonce=' + stm_lms_nonces['stm_lms_add_user_ent_courses'];
          vm.$set(course, 'loading', true);
          this.$http.get(url).then(function (response) {
            vm.$set(course, 'added', true);
            vm.$set(course, 'loading', false);
          });
        },
        changeAdmin: function changeAdmin(user) {
          var vm = this;
          if (!confirm(vm.translations['admin_notice'])) return false;
          var url = stm_lms_ajaxurl + '?action=stm_lms_change_ent_group_admin&user_id=' + user.id + '&group_id=' + vm.group_id + '&nonce=' + stm_lms_nonces['stm_lms_change_ent_group_admin'];
          vm.$set(user, 'loading', true);
          this.$http.get(url).then(function (response) {
            window.location.replace(response.body);
          });
        },
        removeFromGroup: function removeFromGroup(user, index) {
          var vm = this;
          if (!confirm(vm.translations['remove_notice'])) return false;
          var url = stm_lms_ajaxurl + '?action=stm_lms_delete_user_from_group&user_id=' + user.id + '&user_email=' + user.email + '&group_id=' + vm.group_id + '&nonce=' + stm_lms_nonces['stm_lms_delete_user_from_group'];
          vm.$set(user, 'loading', true);
          this.$http.get(url).then(function (response) {
            vm.group.users.splice(index, 1);
          });
        }
      }
    });
  });
})(jQuery);